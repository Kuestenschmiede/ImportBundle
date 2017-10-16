<?php
/**
 * con4gis
 * @version   php 7
 * @package   con4gis
 * @author    con4gis authors (see "authors.txt")
 * @copyright Küstenschmiede GmbH Software & Design 2017
 * @link      https://www.kuestenschmiede.de
 */
namespace con4gis\ImportBundle\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * Class TlC4gImport
 *
 * @ORM\Entity
 * @ORM\Table(name="tl_c4g_import")
 * @package con4gis\ImportBundle\Entity
 */
class TlC4gImport
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
     * @ORM\Column(type="integer")
     */
    protected $tstamp = 0;


    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $title = '';


    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $description = '';


    /**
     * @var resource
     * @ORM\Column(type="blob")
     */
    protected $srcfile = null;


    /**
     * @var string
     * @ORM\Column(type="string", length=1)
     */
    protected $headerline = '';


    /**
     * @var string
     * @ORM\Column(type="string", length=1)
     */
    protected $renamefile = '';


    /**
     * @var string
     * @ORM\Column(type="string", length=1)
     */
    protected $truncatetable = '';


    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $srctable = '';


    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $namedfields = '';


    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $sourcekind = '';


    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $srctablename = '';


    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $fieldnames = '';


    /**
     * Array für die Ablage zusätzlicher Daten.
     * @var string
     * @ORM\Column(type="string")
     */
    protected $additionaldata = '';


    /**
     * Trennzeichen des CSV-Strings
     * @var string
     * @ORM\Column(type="string", length=1)
     */
    protected $delimiter = ';';


    /**
     * Texteinschlusszeichen des CSV-Strings
     * @var string
     * @ORM\Column(type="string", length=1)
     */
    protected $enclosure = '"';


    /**
     * Verarbeitung über Queue
     * @var string
     * @ORM\Column(type="string", length=1)
     */
    protected $usequeue = '';


    /**
     * Verarbeitungsintervall in der Queue benutzen
     * @var string
     * @ORM\Column(type="string", length=1)
     */
    protected $useinterval = '';


    /**
     * Verarbeitungsintervall in der Queue
     * @var string
     * @ORM\Column(type="string")
     */
    protected $intervalkind = '';


    /**
     * Verarbeitungsanzahl in der Queue
     * @var string
     * @ORM\Column(type="string")
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
        return deserialize($this->namedfields, true);
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
        return deserialize($this->fieldnames, true);
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
        $data = deserialize($this->additionaldata, true);

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
}
