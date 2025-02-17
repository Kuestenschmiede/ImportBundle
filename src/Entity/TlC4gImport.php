<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 10
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2025, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */
namespace con4gis\ImportBundle\Entity;

use \Doctrine\ORM\Mapping as ORM;
use con4gis\CoreBundle\Entity\BaseEntity;
use Contao\StringUtil;

/**
 * Class TlC4gImport
 *
 * @ORM\Entity
 * @ORM\Table(name="tl_c4g_import")
 * @package con4gis\ImportBundle\Entity
 */
class TlC4gImport extends BaseEntity
{


    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id = 0;


    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false, options={"unsigned":true, "default":0})
     */
    protected $tstamp = 0;


    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $title = '';


    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description = '';


    /**
     * @var resource
     * @ORM\Column(type="blob", nullable=true)
     */
    protected $srcfile = null;


    /**
     * @var string
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    protected $headerline = '0';


    /**
     * @var string
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    protected $renamefile = '0';


    /**
     * @var string
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    protected $truncatetable = '0';


    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $srctable = '';


    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $namedfields = '';

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $mapStructure = 0;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $locstyleField = '';

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $nameField = '';

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $tooltipField = '';

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $structureField = '';

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $popupFields = '';

    /**
     * @var string
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    protected $importaddresses = '0';
    
    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $addressfields = '';
    
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $geoxfield = '';
    
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $geoyfield = '';

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $sourcekind = '';


    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $srctablename = '';


    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $fieldnames = '';


    /**
     * Array für die Ablage zusätzlicher Daten.
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $additionaldata = '';


    /**
     * Trennzeichen des CSV-Strings
     * @var string
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    protected $delimiter = ';';


    /**
     * Texteinschlusszeichen des CSV-Strings
     * @var string
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    protected $enclosure = '"';

    /**
     * @var string
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    protected $saveTlTables = '0';


    /**
     * Verarbeitung über Queue
     * @var string
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    protected $usequeue = '';


    /**
     * Verarbeitungsintervall in der Queue benutzen
     * @var string
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    protected $useinterval = '';


    /**
     * Verarbeitungsintervall in der Queue
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $intervalkind = '';


    /**
     * Verarbeitungsanzahl in der Queue
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $intervalcount = '';


    /**
     * Setzt die Daten eines Arrays als Eigenschaften der Klasse.
     * @param $data
     */
    public function setData($data)
    {
        if (is_array($data) && count($data)) {
            foreach ($data as $column => $value) {
                if (property_exists($this, $column)) {
                    $column = 'set' . ucfirst($column);
                    $this->$column($value);
                } else {
                    $this->addAdditionaldata($column, $value);
                }
            }
        }
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }


    /**
     * @return int
     */
    public function getTstamp(): int
    {
        return $this->tstamp;
    }


    /**
     * @param int $tstamp
     */
    public function setTstamp(int $tstamp)
    {
        $this->tstamp = $tstamp;
    }


    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }


    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }


    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }


    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }


    /**
     * @return resource
     */
    public function getSrcfile()
    {
        if (is_resource($this->srcfile)) {
            return stream_get_contents($this->srcfile);
        } else {
            return $this->srcfile;
        }
    }


    /**
     * @param resource $srcfile
     */
    public function setSrcfile($srcfile)
    {
        $this->srcfile = $srcfile;
    }


    /**
     * @return string
     */
    public function getHeaderline(): string
    {
        return $this->headerline;
    }


    /**
     * @param string $headerline
     */
    public function setHeaderline(string $headerline)
    {
        $this->headerline = $headerline;
    }


    /**
     * @return string
     */
    public function getRenamefile(): string
    {
        return $this->renamefile;
    }


    /**
     * @param string $renamefile
     */
    public function setRenamefile(string $renamefile)
    {
        $this->renamefile = $renamefile;
    }


    /**
     * @return string
     */
    public function getTruncatetable(): string
    {
        return $this->truncatetable;
    }


    /**
     * @param string $truncatetable
     */
    public function setTruncatetable(string $truncatetable)
    {
        $this->truncatetable = $truncatetable;
    }


    /**
     * @return string
     */
    public function getSrctable(): string
    {
        return $this->srctable;
    }


    /**
     * @param string $srctable
     */
    public function setSrctable(string $srctable)
    {
        $this->srctable = $srctable;
    }


    /**
     * @return array
     */
    public function getNamedfields(): array
    {
        return StringUtil::deserialize($this->namedfields, true);
    }


    /**
     * @param array $namedfields
     */
    public function setNamedfields(array $namedfields)
    {
        $this->namedfields = serialize($namedfields);
    }


    /**
     * @return string
     */
    public function getSourcekind(): string
    {
        return $this->sourcekind;
    }


    /**
     * @param string $sourcekind
     */
    public function setSourcekind(string $sourcekind)
    {
        $this->sourcekind = $sourcekind;
    }


    /**
     * @return string
     */
    public function getSrctablename(): string
    {
        return $this->srctablename;
    }


    /**
     * @param string $srctablename
     */
    public function setSrctablename(string $srctablename)
    {
        $this->srctablename = $srctablename;
    }


    /**
     * @return array
     */
    public function getFieldnames(): array
    {
        return StringUtil::deserialize($this->fieldnames, true);
    }


    /**
     * @param array $fieldnames
     */
    public function setFieldnames(array $fieldnames)
    {
        $this->fieldnames = serialize($fieldnames);
    }


    /**
     * @param null $name
     * @return mixed|null
     */
    public function getAdditionaldata($name = null)
    {
        $data = StringUtil::deserialize($this->additionaldata, true);

        if ($name) {
            if (isset($data[$name])) {
                return $data[$name];
            } else {
                return null;
            }
        } else {
            return $data;
        }
    }


    /**
     * @param array $additionaldata
     */
    public function setAdditionaldata(array $additionaldata)
    {
        $this->additionaldata = serialize($additionaldata);
    }


    /**
     * Setzt einen einzelnen Wert im Array für die zusätzlichen Daten.
     * @param $name
     * @param $value
     */
    public function addAdditionaldata($name, $value)
    {
        $data           = $this->getAdditionaldata();
        $data[$name]    = $value;
        $this->setAdditionaldata($data);
    }


    /**
     * @return string
     */
    public function getDelimiter(): string
    {
        return $this->delimiter;
    }


    /**
     * @param string $delimiter
     */
    public function setDelimiter(string $delimiter)
    {
        $this->delimiter = $delimiter;
    }


    /**
     * @return string
     */
    public function getEnclosure(): string
    {
        return $this->enclosure;
    }


    /**
     * @param string $enclosure
     */
    public function setEnclosure(string $enclosure)
    {
        $this->enclosure = $enclosure;
    }


    /**
     * @return string
     */
    public function getUsequeue(): string
    {
        return $this->usequeue;
    }


    /**
     * @param string $usequeue
     */
    public function setUsequeue(string $usequeue)
    {
        $this->usequeue = $usequeue;
    }


    /**
     * @return string
     */
    public function getUseinterval(): string
    {
        return $this->useinterval;
    }


    /**
     * @param string $useinterval
     */
    public function setUseinterval(string $useinterval)
    {
        $this->useinterval = $useinterval;
    }


    /**
     * @return string
     */
    public function getIntervalkind(): string
    {
        return $this->intervalkind;
    }


    /**
     * @param string $intervalkind
     */
    public function setIntervalkind(string $intervalkind)
    {
        $this->intervalkind = $intervalkind;
    }


    /**
     * @return string
     */
    public function getIntervalcount(): string
    {
        return $this->intervalcount;
    }


    /**
     * @param string $intervalcount
     */
    public function setIntervalcount(string $intervalcount)
    {
        $this->intervalcount = $intervalcount;
    }
    
    /**
     * @return string
     */
    public function getImportaddresses(): string
    {
        return $this->importaddresses ?: '';
    }
    
    /**
     * @param string $importaddresses
     */
    public function setImportaddresses(string $importaddresses): void
    {
        $this->importaddresses = $importaddresses;
    }
    
    /**
     * @return string
     */
    public function getAddressfields(): string
    {
        return $this->addressfields;
    }
    
    /**
     * @param string $addressfields
     */
    public function setAddressfields(string $addressfields): void
    {
        $this->addressfields = $addressfields;
    }
    
    /**
     * @return string
     */
    public function getGeoxfield(): string
    {
        return $this->geoxfield;
    }
    
    /**
     * @param string $geoxfield
     */
    public function setGeoxfield(string $geoxfield): void
    {
        $this->geoxfield = $geoxfield;
    }
    
    /**
     * @return string
     */
    public function getGeoyfield(): string
    {
        return $this->geoyfield;
    }
    
    /**
     * @param string $geoyfield
     */
    public function setGeoyfield(string $geoyfield): void
    {
        $this->geoyfield = $geoyfield;
    }

    public function getMapStructure(): int
    {
        return $this->mapStructure;
    }

    public function setMapStructure(int $mapStructure): void
    {
        $this->mapStructure = $mapStructure;
    }

    public function getLocstyleField(): string
    {
        return $this->locstyleField;
    }

    public function setLocstyleField(string $locstyleField): void
    {
        $this->locstyleField = $locstyleField;
    }

    public function getTooltipField(): string
    {
        return $this->tooltipField;
    }

    public function setTooltipField(string $tooltipField): void
    {
        $this->tooltipField = $tooltipField;
    }

    public function getStructureField(): string
    {
        return $this->structureField;
    }

    public function setStructureField(string $structureField): void
    {
        $this->structureField = $structureField;
    }

    public function getNameField(): string
    {
        return $this->nameField;
    }

    public function setNameField(string $nameField): void
    {
        $this->nameField = $nameField;
    }

    public function getPopupFields(): string
    {
        return $this->popupFields;
    }

    public function setPopupFields(string $popupFields): void
    {
        $this->popupFields = $popupFields;
    }
}
