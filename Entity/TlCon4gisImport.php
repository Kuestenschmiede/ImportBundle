<?php
/**
 * con4gis
 * @version   2.0.0
 * @package   con4gis
 * @author    con4gis authors (see "authors.txt")
 * @copyright Küstenschmiede GmbH Software & Design 2016 - 2017.
 * @link      https://www.kuestenschmiede.de
 */
namespace con4gis\ImportBundle\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * Class TlCon4gisImport
 *
 * @ORM\Entity
 * @ORM\Table(name="tl_con4gis_import")
 * @package con4gis\ImportBundle\Entity
 */
class TlCon4gisImport
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
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $groupid = 0;


    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $memberid = 0;


    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $uuid = '';


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
     * @var array
     * @ORM\Column(type="array")
     */
    protected $namedfields = array();


    /**
     * Array für die Ablage zusätzlicher Daten.
     * @var string
     * @ORM\Column(type="string")
     */
    protected $additionaldata = '';


    /**
     * @var string
     * @ORM\Column(type="string", length=1)
     */
    protected $delimiter = ';';


    /**
     * @var string
     * @ORM\Column(type="string", length=1)
     */
    protected $enclosure = '"';


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
     * @return int
     */
    public function getGroupid(): int
    {
        return $this->groupid;
    }


    /**
     * @param int $groupid
     */
    public function setGroupid(int $groupid)
    {
        $this->groupid = $groupid;
    }


    /**
     * @return int
     */
    public function getMemberid(): int
    {
        return $this->memberid;
    }


    /**
     * @param int $memberid
     */
    public function setMemberid(int $memberid)
    {
        $this->memberid = $memberid;
    }


    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }


    /**
     * @param string $uuid
     */
    public function setUuid(string $uuid)
    {
        $this->uuid = $uuid;
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
        return $this->namedfields;
    }


    /**
     * @param array $namedfields
     */
    public function setNamedfields(array $namedfields)
    {
        $this->namedfields = $namedfields;
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
}
