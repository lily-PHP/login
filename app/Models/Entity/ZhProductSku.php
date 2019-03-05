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
 * 产品属性

 * @Entity()
 * @Table(name="zh_product_sku")
 * @uses      ZhProductSku
 */
class ZhProductSku extends Model
{
    /**
     * @var int $zpsId 
     * @Id()
     * @Column(name="zps_id", type="integer")
     */
    private $zpsId;

    /**
     * @var int $zpsAdvertId 关联zh_product表zp_id字段
     * @Column(name="zps_advertId", type="integer", default=0)
     */
    private $zpsAdvertId;

    /**
     * @var string $zpsTitle 标题
     * @Column(name="zps_title", type="string", length=255)
     * @Required()
     */
    private $zpsTitle;

    /**
     * @var string $zpsSku sku
     * @Column(name="zps_sku", type="string", length=32)
     * @Required()
     */
    private $zpsSku;

    /**
     * @var string $zpsHashSku hash sku(大洲+国家+sku)
     * @Column(name="zps_hash_sku", type="string", length=32)
     */
    private $zpsHashSku;

    /**
     * @var int $zpsInventory 库存
     * @Column(name="zps_inventory", type="integer")
     */
    private $zpsInventory;

    /**
     * @var float $zpsPrice 售价
     * @Column(name="zps_price", type="decimal")
     * @Required()
     */
    private $zpsPrice;

    /**
     * @var float $zpsUnlinePrice 划线价
     * @Column(name="zps_unlinePrice", type="decimal")
     */
    private $zpsUnlinePrice;

    /**
     * @var int $zpsSalesvolume 销售量
     * @Column(name="zps_salesvolume", type="integer")
     */
    private $zpsSalesvolume;

    /**
     * @var string $zpsUnit 计算单位
     * @Column(name="zps_unit", type="string", length=45)
     */
    private $zpsUnit;

    /**
     * @var string $zpsTips 
     * @Column(name="zps_tips", type="string", length=255)
     */
    private $zpsTips;

    /**
     * @var int $zpsClassifyId 
     * @Column(name="zps_classifyId", type="integer")
     */
    private $zpsClassifyId;

    /**
     * @var string $zpsClassifyOther 
     * @Column(name="zps_classify_other", type="string", length=255)
     */
    private $zpsClassifyOther;

    /**
     * @var string $zpsAttribute 规格
     * @Column(name="zps_attribute", type="text", length=65535)
     */
    private $zpsAttribute;

    /**
     * @param int $value
     * @return $this
     */
    public function setZpsId(int $value)
    {
        $this->zpsId = $value;

        return $this;
    }

    /**
     * 关联zh_product表zp_id字段
     * @param int $value
     * @return $this
     */
    public function setZpsAdvertId(int $value): self
    {
        $this->zpsAdvertId = $value;

        return $this;
    }

    /**
     * 标题
     * @param string $value
     * @return $this
     */
    public function setZpsTitle(string $value): self
    {
        $this->zpsTitle = $value;

        return $this;
    }

    /**
     * sku
     * @param string $value
     * @return $this
     */
    public function setZpsSku(string $value): self
    {
        $this->zpsSku = $value;

        return $this;
    }

    /**
     * hash sku(大洲+国家+sku)
     * @param string $value
     * @return $this
     */
    public function setZpsHashSku(string $value): self
    {
        $this->zpsHashSku = $value;

        return $this;
    }

    /**
     * 库存
     * @param int $value
     * @return $this
     */
    public function setZpsInventory(int $value): self
    {
        $this->zpsInventory = $value;

        return $this;
    }

    /**
     * 售价
     * @param float $value
     * @return $this
     */
    public function setZpsPrice(float $value): self
    {
        $this->zpsPrice = $value;

        return $this;
    }

    /**
     * 划线价
     * @param float $value
     * @return $this
     */
    public function setZpsUnlinePrice(float $value): self
    {
        $this->zpsUnlinePrice = $value;

        return $this;
    }

    /**
     * 销售量
     * @param int $value
     * @return $this
     */
    public function setZpsSalesvolume(int $value): self
    {
        $this->zpsSalesvolume = $value;

        return $this;
    }

    /**
     * 计算单位
     * @param string $value
     * @return $this
     */
    public function setZpsUnit(string $value): self
    {
        $this->zpsUnit = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setZpsTips(string $value): self
    {
        $this->zpsTips = $value;

        return $this;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setZpsClassifyId(int $value): self
    {
        $this->zpsClassifyId = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setZpsClassifyOther(string $value): self
    {
        $this->zpsClassifyOther = $value;

        return $this;
    }

    /**
     * 规格
     * @param string $value
     * @return $this
     */
    public function setZpsAttribute(string $value): self
    {
        $this->zpsAttribute = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getZpsId()
    {
        return $this->zpsId;
    }

    /**
     * 关联zh_product表zp_id字段
     * @return int
     */
    public function getZpsAdvertId()
    {
        return $this->zpsAdvertId;
    }

    /**
     * 标题
     * @return string
     */
    public function getZpsTitle()
    {
        return $this->zpsTitle;
    }

    /**
     * sku
     * @return string
     */
    public function getZpsSku()
    {
        return $this->zpsSku;
    }

    /**
     * hash sku(大洲+国家+sku)
     * @return string
     */
    public function getZpsHashSku()
    {
        return $this->zpsHashSku;
    }

    /**
     * 库存
     * @return int
     */
    public function getZpsInventory()
    {
        return $this->zpsInventory;
    }

    /**
     * 售价
     * @return float
     */
    public function getZpsPrice()
    {
        return $this->zpsPrice;
    }

    /**
     * 划线价
     * @return float
     */
    public function getZpsUnlinePrice()
    {
        return $this->zpsUnlinePrice;
    }

    /**
     * 销售量
     * @return int
     */
    public function getZpsSalesvolume()
    {
        return $this->zpsSalesvolume;
    }

    /**
     * 计算单位
     * @return string
     */
    public function getZpsUnit()
    {
        return $this->zpsUnit;
    }

    /**
     * @return string
     */
    public function getZpsTips()
    {
        return $this->zpsTips;
    }

    /**
     * @return int
     */
    public function getZpsClassifyId()
    {
        return $this->zpsClassifyId;
    }

    /**
     * @return string
     */
    public function getZpsClassifyOther()
    {
        return $this->zpsClassifyOther;
    }

    /**
     * 规格
     * @return string
     */
    public function getZpsAttribute()
    {
        return $this->zpsAttribute;
    }

}
