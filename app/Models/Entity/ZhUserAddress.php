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
 * 用户收货地址

 * @Entity()
 * @Table(name="zh_user_address")
 * @uses      ZhUserAddress
 */
class ZhUserAddress extends Model
{
    /**
     * @var int $uaId 
     * @Id()
     * @Column(name="ua_id", type="integer")
     */
    private $uaId;

    /**
     * @var int $zuId 
     * @Column(name="zu_id", type="integer")
     */
    private $zuId;

    /**
     * @var string $uaCountry 国家
     * @Column(name="ua_country", type="string", length=128)
     */
    private $uaCountry;

    /**
     * @var string $uaProvince 省/洲
     * @Column(name="ua_province", type="string", length=128)
     */
    private $uaProvince;

    /**
     * @var string $uaCity 市
     * @Column(name="ua_city", type="string", length=128)
     */
    private $uaCity;

    /**
     * @var string $uaArea 区
     * @Column(name="ua_area", type="string", length=128)
     */
    private $uaArea;

    /**
     * @var string $uaAddress 详细地址
     * @Column(name="ua_address", type="string", length=255)
     */
    private $uaAddress;

    /**
     * @var string $uaPost 邮编
     * @Column(name="ua_post", type="string", length=10)
     */
    private $uaPost;

    /**
     * @var int $uaStatus 状态：0正常，1停用
     * @Column(name="ua_status", type="integer", default=0)
     */
    private $uaStatus;

    /**
     * @param int $value
     * @return $this
     */
    public function setUaId(int $value)
    {
        $this->uaId = $value;

        return $this;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setZuId(int $value): self
    {
        $this->zuId = $value;

        return $this;
    }

    /**
     * 国家
     * @param string $value
     * @return $this
     */
    public function setUaCountry(string $value): self
    {
        $this->uaCountry = $value;

        return $this;
    }

    /**
     * 省/洲
     * @param string $value
     * @return $this
     */
    public function setUaProvince(string $value): self
    {
        $this->uaProvince = $value;

        return $this;
    }

    /**
     * 市
     * @param string $value
     * @return $this
     */
    public function setUaCity(string $value): self
    {
        $this->uaCity = $value;

        return $this;
    }

    /**
     * 区
     * @param string $value
     * @return $this
     */
    public function setUaArea(string $value): self
    {
        $this->uaArea = $value;

        return $this;
    }

    /**
     * 详细地址
     * @param string $value
     * @return $this
     */
    public function setUaAddress(string $value): self
    {
        $this->uaAddress = $value;

        return $this;
    }

    /**
     * 邮编
     * @param string $value
     * @return $this
     */
    public function setUaPost(string $value): self
    {
        $this->uaPost = $value;

        return $this;
    }

    /**
     * 状态：0正常，1停用
     * @param int $value
     * @return $this
     */
    public function setUaStatus(int $value): self
    {
        $this->uaStatus = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUaId()
    {
        return $this->uaId;
    }

    /**
     * @return int
     */
    public function getZuId()
    {
        return $this->zuId;
    }

    /**
     * 国家
     * @return string
     */
    public function getUaCountry()
    {
        return $this->uaCountry;
    }

    /**
     * 省/洲
     * @return string
     */
    public function getUaProvince()
    {
        return $this->uaProvince;
    }

    /**
     * 市
     * @return string
     */
    public function getUaCity()
    {
        return $this->uaCity;
    }

    /**
     * 区
     * @return string
     */
    public function getUaArea()
    {
        return $this->uaArea;
    }

    /**
     * 详细地址
     * @return string
     */
    public function getUaAddress()
    {
        return $this->uaAddress;
    }

    /**
     * 邮编
     * @return string
     */
    public function getUaPost()
    {
        return $this->uaPost;
    }

    /**
     * 状态：0正常，1停用
     * @return int
     */
    public function getUaStatus()
    {
        return $this->uaStatus;
    }

}
