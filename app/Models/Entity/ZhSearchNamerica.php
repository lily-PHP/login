<?php
namespace App\Models\Entity;

use Swoft\Db\Model;
use Swoft\Db\Bean\Annotation\Column;
use Swoft\Db\Bean\Annotation\Entity;
use Swoft\Db\Bean\Annotation\Id;
use Swoft\Db\Bean\Annotation\Required;
use Swoft\Db\Bean\Annotation\Table;
use Swoft\Db\Types;

/**
{{entityName}}
 * @Entity()
 * @Table(name="zh_search_namerica")
 * @uses      ZhSearchNamerica
 */
class ZhSearchNamerica extends Model
{
    /**
{{comment}}
{{@Id}}
     * @Column(name="zs_attribute_a", type="string", length=45)
{{@Required}}
     */
    private $zsAttributeA;

    /**
{{comment}}
{{@Id}}
     * @Column(name="zs_attribute_b", type="string", length=45)
{{@Required}}
     */
    private $zsAttributeB;

    /**
{{comment}}
{{@Id}}
     * @Column(name="zs_attribute_c", type="string", length=45)
{{@Required}}
     */
    private $zsAttributeC;

    /**
{{comment}}
{{@Id}}
     * @Column(name="zs_evaluate", type="integer", default=0)
{{@Required}}
     */
    private $zsEvaluate;

    /**
{{comment}}
{{@Id}}
     * @Column(name="zs_hot", type="integer", default=0)
{{@Required}}
     */
    private $zsHot;

    /**
{{comment}}
{{@Id}}
     * @Column(name="zs_id", type="integer")
{{@Required}}
     */
    private $zsId;

    /**
{{comment}}
{{@Id}}
     * @Column(name="zs_product_id", type="integer")
{{@Required}}
     */
    private $zsProductId;

    /**
{{comment}}
{{@Id}}
     * @Column(name="zs_salesvolume", type="integer", default=0)
{{@Required}}
     */
    private $zsSalesvolume;

    /**
{{comment}}
{{@Id}}
     * @Column(name="zs_service", type="string", length=64)
{{@Required}}
     */
    private $zsService;

    /**
{{comment}}
{{@Id}}
     * @Column(name="zs_time", type="datetime")
{{@Required}}
     */
    private $zsTime;

    /**
{{comment}}
{{@Id}}
     * @Column(name="zu_country", type="string", length=5)
{{@Required}}
     */
    private $zuCountry;

    /**
{{comment}}
     * @param string $value
     * @return $this
     */
    public function setZsAttributeA(string $value): self
    {
        $this->zsAttributeA = $value;

        return $this;
    }

    /**
{{comment}}
     * @param string $value
     * @return $this
     */
    public function setZsAttributeB(string $value): self
    {
        $this->zsAttributeB = $value;

        return $this;
    }

    /**
{{comment}}
     * @param string $value
     * @return $this
     */
    public function setZsAttributeC(string $value): self
    {
        $this->zsAttributeC = $value;

        return $this;
    }

    /**
{{comment}}
     * @param int $value
     * @return $this
     */
    public function setZsEvaluate(int $value): self
    {
        $this->zsEvaluate = $value;

        return $this;
    }

    /**
{{comment}}
     * @param int $value
     * @return $this
     */
    public function setZsHot(int $value): self
    {
        $this->zsHot = $value;

        return $this;
    }

    /**
{{comment}}
     * @param int $value
     * @return $this
     */
    public function setZsId(int $value)
    {
        $this->zsId = $value;

        return $this;
    }

    /**
{{comment}}
     * @param int $value
     * @return $this
     */
    public function setZsProductId(int $value): self
    {
        $this->zsProductId = $value;

        return $this;
    }

    /**
{{comment}}
     * @param int $value
     * @return $this
     */
    public function setZsSalesvolume(int $value): self
    {
        $this->zsSalesvolume = $value;

        return $this;
    }

    /**
{{comment}}
     * @param string $value
     * @return $this
     */
    public function setZsService(string $value): self
    {
        $this->zsService = $value;

        return $this;
    }

    /**
{{comment}}
     * @param string $value
     * @return $this
     */
    public function setZsTime(string $value): self
    {
        $this->zsTime = $value;

        return $this;
    }

    /**
{{comment}}
     * @param string $value
     * @return $this
     */
    public function setZuCountry(string $value): self
    {
        $this->zuCountry = $value;

        return $this;
    }

    /**
{{comment}}
     * @return string
     */
    public function getZsAttributeA()
    {
        return $this->zsAttributeA;
    }

    /**
{{comment}}
     * @return string
     */
    public function getZsAttributeB()
    {
        return $this->zsAttributeB;
    }

    /**
{{comment}}
     * @return string
     */
    public function getZsAttributeC()
    {
        return $this->zsAttributeC;
    }

    /**
{{comment}}
     * @return int
     */
    public function getZsEvaluate()
    {
        return $this->zsEvaluate;
    }

    /**
{{comment}}
     * @return int
     */
    public function getZsHot()
    {
        return $this->zsHot;
    }

    /**
{{comment}}
     * @return mixed
     */
    public function getZsId()
    {
        return $this->zsId;
    }

    /**
{{comment}}
     * @return int
     */
    public function getZsProductId()
    {
        return $this->zsProductId;
    }

    /**
{{comment}}
     * @return int
     */
    public function getZsSalesvolume()
    {
        return $this->zsSalesvolume;
    }

    /**
{{comment}}
     * @return string
     */
    public function getZsService()
    {
        return $this->zsService;
    }

    /**
{{comment}}
     * @return string
     */
    public function getZsTime()
    {
        return $this->zsTime;
    }

    /**
{{comment}}
     * @return string
     */
    public function getZuCountry()
    {
        return $this->zuCountry;
    }

}
