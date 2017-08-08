<?php
/**
 * con4gis
 * @version   2.0.0
 * @package   con4gis
 * @author    con4gis authors (see "authors.txt")
 * @copyright Küstenschmiede GmbH Software & Design 2016 - 2017.
 * @link      https://www.kuestenschmiede.de
 */
namespace con4gis\ImportBundle\Controller;

use con4gis\ImportBundle\Classes\Contao\Modules\ModulImport;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ApiController
 * @package con4gis\ImportBundle\Controller
 */
class ApiController extends Controller
{


    /**
     * initialize contao
     */
    protected function initialize()
    {
        $this->container->get('contao.framework')->initialize();
    }


    /**
     * Ruft das Event für den Import auf.
     * @param $id
     * @return JsonResponse
     */
    public function runImportAction($id)
    {
        $this->initialize();
        $export = new ModulImport();
        $export->setImportId($id);
        $output = $export->runImport(false);
        return $this->sendResponse([$output]);
    }


    /**
     * Erstellt das Response.
     * @param       $output
     * @param int   $status
     * @param array $header
     * @return JsonResponse
     */
    protected function sendResponse($output, $status = 200, $header = array())
    {
        $status = ($status == 200 && (!is_array($output)) || !count($output)) ? 204 : $status;
        $data   = json_encode(['output' => $output]);
        return new JsonResponse($data, $status, $header);
    }
}
