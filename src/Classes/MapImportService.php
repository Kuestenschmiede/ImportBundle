<?php

namespace con4gis\ImportBundle\Classes;

use con4gis\CoreBundle\Classes\C4GUtils;
use con4gis\ImportBundle\Entity\TlC4gImport;
use Contao\FilesModel;
use Contao\StringUtil;
use Doctrine\DBAL\Connection;

class MapImportService
{

    public function __construct(
        private Connection $connection
    ) {}

    public function importIntoMapStructure(TlC4gImport $importConfig): int
    {
        $dataCount = 0;
        $dataFile = $importConfig->getSrcfile();

        if (!$dataFile) {
            return $dataCount;
        }

        $dataFileModel = FilesModel::findByUuid(StringUtil::binToUuid($dataFile));
        $path = $dataFileModel->path;
        $file = fopen($path, 'r');
        $csvContent = \Safe\fgetcsv($file, null, $importConfig->getDelimiter(), $importConfig->getEnclosure());
        $headerFields = [];

        if ($importConfig->getHeaderline()) {
            $headerFields = $csvContent;
            $csvContent = \Safe\fgetcsv($file, null, $importConfig->getDelimiter(), $importConfig->getEnclosure());
        }

        while ($csvContent !== false) {
            // process current line
            $dataEntry = [];
            foreach ($csvContent as $key => $item) {
                $csvContent[$key] = mb_convert_encoding($item, 'UTF-8', mb_detect_encoding($item));

                if ($headerFields) {
                    $dataEntry[$headerFields[$key]] = $item;
                } else {
                    // problem
                    $dataEntry[$key] = $item;
                }
            }

            // convert data and insert into db
            $rowsInserted = $this->processDataEntry($dataEntry, $importConfig);
            $dataCount += $rowsInserted;

            // read next line
            $csvContent = \Safe\fgetcsv($file, null, $importConfig->getDelimiter(), $importConfig->getEnclosure());
        }

        fclose($file);

        return $dataCount;
    }

    private function processDataEntry(array $dataEntry, TlC4gImport $importConfig)
    {
        $elementExists = false;
        $elementId = null;
        $locstyleId = 0;
        $dataName = $dataEntry[$importConfig->getNameField()];

        //check if element with that name already exists
        $sql = "SELECT * FROM tl_c4g_maps WHERE `name` = ?";
        $mapResult = $this->connection->executeQuery($sql, [$dataName])
            ->fetchAllAssociative();

        if (count($mapResult) > 0 && $mapResult[0]['id']) {
            // element already exists, don't insert
            $elementExists = true;
            $elementId = $mapResult[0]['id'];
            $locstyleId = $mapResult[0]['locstyle'];
        }

        // convert addresses
        if ($importConfig->getImportaddresses()) {
            $addressFields = StringUtil::deserialize($importConfig->getAddressfields(), true);
            $addressValue = "";

            foreach ($addressFields as $addressField) {
                $addressValue .= $dataEntry[$addressField] . " ";
            }

            $coordinates = C4GUtils::geocodeAddress($addressValue);
            $geox = $coordinates[0];
            $geoy = $coordinates[1];
        } else {
            $geox = "";
            $geoy = "";
        }

        // check map structure
        $pid = $importConfig->getMapStructure();
        if ($importConfig->getStructureField()) {
            $structureField = $importConfig->getStructureField();
            $mapStructureName = $dataEntry[$structureField];

            $sql = "SELECT * FROM tl_c4g_maps WHERE `name` = ?";
            $mapResult = $this->connection->executeQuery($sql, [$mapStructureName])
                ->fetchAllAssociative();
            if (count($mapResult) > 0 && $mapResult[0]['id']) {
                $pid = $mapResult[0]['id'];
            } else {
                $structureData = [
                    'name' => $mapStructureName,
                    'pid' => $pid,
                    'location_type' => 'none',
                    'tstamp' => time(),
                ];

                $this->connection->insert('tl_c4g_maps', $structureData);
                $pid = $this->connection->lastInsertId();
            }
        }

        // check popup content
        $popupContent = null;
        if ($importConfig->getPopupFields()) {
            $popupFields = StringUtil::deserialize($importConfig->getPopupFields(), true);
            $popupContent = "";
            foreach ($popupFields as $popupField) {
                $value = $dataEntry[$popupField];
                $value = "<p>" . $value . "</p>";
                $popupContent .= $value;
            }
        }

        // check for available locstyle by name
        if ($importConfig->getLocstyleField()) {
            $locstyleFieldname = $importConfig->getLocstyleField();
            $locstyleName = $dataEntry[$locstyleFieldname];

            $sql = "SELECT * FROM tl_c4g_map_locstyles WHERE `name` = ?";
            $styleResult = $this->connection->executeQuery($sql, [$locstyleName])
                ->fetchAllAssociative();

            if (count($styleResult) > 0 && $styleResult[0]['id']) {
                $locstyleId = $styleResult[0]['id'];
            }
        }

        // map fields
        $mapData = [
            'name' => $dataName,
            'loc_geox' => $geox,
            'loc_geoy' => $geoy,
            'location_type' => 'single',
            'pid' => $pid,
            'locstyle' => $locstyleId,
            'tooltip' => $dataEntry[$importConfig->getTooltipField()],
            'popup_info' => $popupContent
        ];

        if ($elementExists) {
            return (int) $this->connection->update("tl_c4g_maps", $mapData, ['id' => $elementId]);
        } else {
            return (int) $this->connection->insert("tl_c4g_maps", $mapData);
        }
    }
}