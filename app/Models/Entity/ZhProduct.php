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
 * 产品表

 * @Entity()
 * @Table(name="zh_product")
 * @uses      ZhProduct
 */
class ZhProduct extends Model
{
    /**
     * @var int $zpId advert Id
     * @Id()
     * @Column(name="zp_id", type="integer")
     */
    private $zpId;

    /**
     * @var int $zpPlatformId 平台ID
     * @Column(name="zp_platformId", type="integer")
     * @Required()
     */
    private $zpPlatformId;

    /**
     * @var string $zpTitle 标题
     * @Column(name="zp_title", type="string", length=255)
     * @Required()
     */
    private $zpTitle;

    /**
     * @var string $zpSeoTitle seo标题
     * @Column(name="zp_seoTitle", type="string", length=255)
     */
    private $zpSeoTitle;

    /**
     * @var string $zpContent 文案
     * @Column(name="zp_content", type="text", length=65535)
     */
    private $zpContent;

    /**
     * @var string $zpSkuTips 标签
     * @Column(name="zp_skuTips", type="string", length=45)
     */
    private $zpSkuTips;

    /**
     * @var float $zpPrice 价格
     * @Column(name="zp_price", type="decimal")
     */
    private $zpPrice;

    /**
     * @var float $zpUnlinePrice 划线价
     * @Column(name="zp_unlinePrice", type="decimal")
     */
    private $zpUnlinePrice;

    /**
     * @var int $zpDiscount 折扣：0无、1有
     * @Column(name="zp_discount", type="integer")
     */
    private $zpDiscount;

    /**
     * @var int $zpDiscountType 折扣类型：0倒计时、 1限时
     * @Column(name="zp_discountType", type="integer")
     */
    private $zpDiscountType;

    /**
     * @var int $zpDiscountTime 折扣时间/折扣结束时间(限时)
     * @Column(name="zp_discountTime", type="integer")
     */
    private $zpDiscountTime;

    /**
     * @var int $zpDiscountStartAt 折扣开始时间
     * @Column(name="zp_discountStartAt", type="integer")
     */
    private $zpDiscountStartAt;

    /**
     * @var int $zpEvaluate 评价数量
     * @Column(name="zp_evaluate", type="integer")
     */
    private $zpEvaluate;

    /**
     * @var int $zpSalesvolume 销售数量
     * @Column(name="zp_salesvolume", type="integer")
     */
    private $zpSalesvolume;

    /**
     * @var int $zpHot 热度/点赞/收藏
     * @Column(name="zp_hot", type="integer")
     */
    private $zpHot;

    /**
     * @var string $zpCreateAt 创建时间(在后台创建)
     * @Column(name="zp_create_at", type="datetime")
     */
    private $zpCreateAt;

    /**
     * @var int $zpStatus 产品状态：1正常，2下线
     * @Column(name="zp_status", type="integer", default=1)
     */
    private $zpStatus;

    /**
     * advert Id
     * @param int $value
     * @return $this
     */
    public function setZpId(int $value)
    {
        $this->zpId = $value;

        return $this;
    }

    /**
     * 平台ID
     * @param int $value
     * @return $this
     */
    public function setZpPlatformId(int $value): self
    {
        $this->zpPlatformId = $value;

        return $this;
    }

    /**
     * 标题
     * @param string $value
     * @return $this
     */
    public function setZpTitle(string $value): self
    {
        $this->zpTitle = $value;

        return $this;
    }

    /**
     * seo标题
     * @param string $value
     * @return $this
     */
    public function setZpSeoTitle(string $value): self
    {
        $this->zpSeoTitle = $value;

        return $this;
    }

    /**
     * 文案
     * @param string $value
     * @return $this
     */
    public function setZpContent(string $value): self
    {
        $this->zpContent = $value;

        return $this;
    }

    /**
     * 标签
     * @param string $value
     * @return $this
     */
    public function setZpSkuTips(string $value): self
    {
        $this->zpSkuTips = $value;

        return $this;
    }

    /**
     * 价格
     * @param float $value
     * @return $this
     */
    public function setZpPrice(float $value): self
    {
        $this->zpPrice = $value;

        return $this;
    }

    /**
     * 划线价
     * @param float $value
     * @return $this
     */
    public function setZpUnlinePrice(float $value): self
    {
        $this->zpUnlinePrice = $value;

        return $this;
    }

    /**
     * 折扣：0无、1有
     * @param int $value
     * @return $this
     */
    public function setZpDiscount(int $value): self
    {
        $this->zpDiscount = $value;

        return $this;
    }

    /**
     * 折扣类型：0倒计时、 1限时
     * @param int $value
     * @return $this
     */
    public function setZpDiscountType(int $value): self
    {
        $this->zpDiscountType = $value;

        return $this;
    }

    /**
     * 折扣时间/折扣结束时间(限时)
     * @param int $value
     * @return $this
     */
    public function setZpDiscountTime(int $value): self
    {
        $this->zpDiscountTime = $value;

        return $this;
    }

    /**
     * 折扣开始时间
     * @param int $value
     * @return $this
     */
    public function setZpDiscountStartAt(int $value): self
    {
        $this->zpDiscountStartAt = $value;

        return $this;
    }

    /**
     * 评价数量
     * @param int $value
     * @return $this
     */
    public function setZpEvaluate(int $value): self
    {
        $this->zpEvaluate = $value;

        return $this;
    }

    /**
     * 销售数量
     * @param int $value
     * @return $this
     */
    public function setZpSalesvolume(int $value): self
    {
        $this->zpSalesvolume = $value;

        return $this;
    }

    /**
     * 热度/点赞/收藏
     * @param int $value
     * @return $this
     */
    public function setZpHot(int $value): self
    {
        $this->zpHot = $value;

        return $this;
    }

    /**
     * 创建时间(在后台创建)
     * @param string $value
     * @return $this
     */
    public function setZpCreateAt(string $value): self
    {
        $this->zpCreateAt = $value;

        return $this;
    }

    /**
     * 产品状态：1正常，2下线
     * @param int $value
     * @return $this
     */
    public function setZpStatus(int $value): self
    {
        $this->zpStatus = $value;

        return $this;
    }

    /**
     * advert Id
     * @return mixed
     */
    public function getZpId()
    {
        return $this->zpId;
    }

    /**
     * 平台ID
     * @return int
     */
    public function getZpPlatformId()
    {
        return $this->zpPlatformId;
    }

    /**
     * 标题
     * @return string
     */
    public function getZpTitle()
    {
        return $this->zpTitle;
    }

    /**
     * seo标题
     * @return string
     */
    public function getZpSeoTitle()
    {
        return $this->zpSeoTitle;
    }

    /**
     * 文案
     * @return string
     */
    public function getZpContent()
    {
        return $this->zpContent;
    }

    /**
     * 标签
     * @return string
     */
    public function getZpSkuTips()
    {
        return $this->zpSkuTips;
    }

    /**
     * 价格
     * @return float
     */
    public function getZpPrice()
    {
        return $this->zpPrice;
    }

    /**
     * 划线价
     * @return float
     */
    public function getZpUnlinePrice()
    {
        return $this->zpUnlinePrice;
    }

    /**
     * 折扣：0无、1有
     * @return int
     */
    public function getZpDiscount()
    {
        return $this->zpDiscount;
    }

    /**
     * 折扣类型：0倒计时、 1限时
     * @return int
     */
    public function getZpDiscountType()
    {
        return $this->zpDiscountType;
    }

    /**
     * 折扣时间/折扣结束时间(限时)
     * @return int
     */
    public function getZpDiscountTime()
    {
        return $this->zpDiscountTime;
    }

    /**
     * 折扣开始时间
     * @return int
     */
    public function getZpDiscountStartAt()
    {
        return $this->zpDiscountStartAt;
    }

    /**
     * 评价数量
     * @return int
     */
    public function getZpEvaluate()
    {
        return $this->zpEvaluate;
    }

    /**
     * 销售数量
     * @return int
     */
    public function getZpSalesvolume()
    {
        return $this->zpSalesvolume;
    }

    /**
     * 热度/点赞/收藏
     * @return int
     */
    public function getZpHot()
    {
        return $this->zpHot;
    }

    /**
     * 创建时间(在后台创建)
     * @return string
     */
    public function getZpCreateAt()
    {
        return $this->zpCreateAt;
    }

    /**
     * 产品状态：1正常，2下线
     * @return mixed
     */
    public function getZpStatus()
    {
        return $this->zpStatus;
    }

}
