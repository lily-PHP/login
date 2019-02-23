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
 * @Table(name="zh_user")
 * @uses      ZhUser
 */
class ZhUser extends Model
{
    /**
{{comment}}
{{@Id}}
     * @Column(name="zu_authkey", type="string", length=128)
{{@Required}}
     */
    private $zuAuthkey;

    /**
{{comment}}
{{@Id}}
     * @Column(name="zu_avatar", type="string", length=255, default="")
{{@Required}}
     */
    private $zuAvatar;

    /**
{{comment}}
{{@Id}}
     * @Column(name="zu_country", type="string", length=5)
{{@Required}}
     */
    private $zuCountry;

    /**
{{comment}}
{{@Id}}
     * @Column(name="zu_email", type="string", length=255)
{{@Required}}
     */
    private $zuEmail;

    /**
{{comment}}
{{@Id}}
     * @Column(name="zu_id", type="integer")
{{@Required}}
     */
    private $zuId;

    /**
{{comment}}
{{@Id}}
     * @Column(name="zu_inter", type="string", length=5)
{{@Required}}
     */
    private $zuInter;

    /**
{{comment}}
{{@Id}}
     * @Column(name="zu_password", type="string", length=64)
{{@Required}}
     */
    private $zuPassword;

    /**
{{comment}}
{{@Id}}
     * @Column(name="zu_phone", type="string", length=32)
{{@Required}}
     */
    private $zuPhone;

    /**
{{comment}}
{{@Id}}
     * @Column(name="zu_sex", type="integer")
{{@Required}}
     */
    private $zuSex;

    /**
{{comment}}
{{@Id}}
     * @Column(name="zu_token", type="string", length=128)
{{@Required}}
     */
    private $zuToken;

    /**
{{comment}}
{{@Id}}
     * @Column(name="zu_userfrom", type="string", length=32)
{{@Required}}
     */
    private $zuUserfrom;

    /**
{{comment}}
{{@Id}}
     * @Column(name="zu_userId", type="string", length=64)
{{@Required}}
     */
    private $zuUserId;

    /**
{{comment}}
{{@Id}}
     * @Column(name="zu_username", type="string", length=64)
{{@Required}}
     */
    private $zuUsername;

    /**
{{comment}}
     * @param string $value
     * @return $this
     */
    public function setZuAuthkey(string $value): self
    {
        $this->zuAuthkey = $value;

        return $this;
    }

    /**
{{comment}}
     * @param string $value
     * @return $this
     */
    public function setZuAvatar(string $value): self
    {
        $this->zuAvatar = $value;

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
     * @param string $value
     * @return $this
     */
    public function setZuEmail(string $value): self
    {
        $this->zuEmail = $value;

        return $this;
    }

    /**
{{comment}}
     * @param int $value
     * @return $this
     */
    public function setZuId(int $value)
    {
        $this->zuId = $value;

        return $this;
    }

    /**
{{comment}}
     * @param string $value
     * @return $this
     */
    public function setZuInter(string $value): self
    {
        $this->zuInter = $value;

        return $this;
    }

    /**
{{comment}}
     * @param string $value
     * @return $this
     */
    public function setZuPassword(string $value): self
    {
        $this->zuPassword = $value;

        return $this;
    }

    /**
{{comment}}
     * @param string $value
     * @return $this
     */
    public function setZuPhone(string $value): self
    {
        $this->zuPhone = $value;

        return $this;
    }

    /**
{{comment}}
     * @param int $value
     * @return $this
     */
    public function setZuSex(int $value): self
    {
        $this->zuSex = $value;

        return $this;
    }

    /**
{{comment}}
     * @param string $value
     * @return $this
     */
    public function setZuToken(string $value): self
    {
        $this->zuToken = $value;

        return $this;
    }

    /**
{{comment}}
     * @param string $value
     * @return $this
     */
    public function setZuUserfrom(string $value): self
    {
        $this->zuUserfrom = $value;

        return $this;
    }

    /**
{{comment}}
     * @param string $value
     * @return $this
     */
    public function setZuUserId(string $value): self
    {
        $this->zuUserId = $value;

        return $this;
    }

    /**
{{comment}}
     * @param string $value
     * @return $this
     */
    public function setZuUsername(string $value): self
    {
        $this->zuUsername = $value;

        return $this;
    }

    /**
{{comment}}
     * @return string
     */
    public function getZuAuthkey()
    {
        return $this->zuAuthkey;
    }

    /**
{{comment}}
     * @return string
     */
    public function getZuAvatar()
    {
        return $this->zuAvatar;
    }

    /**
{{comment}}
     * @return string
     */
    public function getZuCountry()
    {
        return $this->zuCountry;
    }

    /**
{{comment}}
     * @return string
     */
    public function getZuEmail()
    {
        return $this->zuEmail;
    }

    /**
{{comment}}
     * @return mixed
     */
    public function getZuId()
    {
        return $this->zuId;
    }

    /**
{{comment}}
     * @return string
     */
    public function getZuInter()
    {
        return $this->zuInter;
    }

    /**
{{comment}}
     * @return string
     */
    public function getZuPassword()
    {
        return $this->zuPassword;
    }

    /**
{{comment}}
     * @return string
     */
    public function getZuPhone()
    {
        return $this->zuPhone;
    }

    /**
{{comment}}
     * @return int
     */
    public function getZuSex()
    {
        return $this->zuSex;
    }

    /**
{{comment}}
     * @return string
     */
    public function getZuToken()
    {
        return $this->zuToken;
    }

    /**
{{comment}}
     * @return string
     */
    public function getZuUserfrom()
    {
        return $this->zuUserfrom;
    }

    /**
{{comment}}
     * @return string
     */
    public function getZuUserId()
    {
        return $this->zuUserId;
    }

    /**
{{comment}}
     * @return string
     */
    public function getZuUsername()
    {
        return $this->zuUsername;
    }

}
