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
 * 区域用户表

 * @Entity()
 * @Table(name="zh_user")
 * @uses      ZhUser
 */
class ZhUser extends Model
{
    /**
     * @var int $zuId 
     * @Id()
     * @Column(name="zu_id", type="integer")
     */
    private $zuId;

    /**
     * @var string $zuUsername 用户名/第三方平台名称
     * @Column(name="zu_username", type="string", length=64)
     * @Required()
     */
    private $zuUsername;

    /**
     * @var string $zuAvatar 头像
     * @Column(name="zu_avatar", type="string", length=255, default="")
     */
    private $zuAvatar;

    /**
     * @var int $zuSex 性别 1男性， 0女性
     * @Column(name="zu_sex", type="integer")
     */
    private $zuSex;

    /**
     * @var string $zuPassword 密码
     * @Column(name="zu_password", type="string", length=64)
     */
    private $zuPassword;

    /**
     * @var string $zuAuthkey 第三方登录生成的密匙
     * @Column(name="zu_authkey", type="string", length=255)
     */
    private $zuAuthkey;

    /**
     * @var string $zuToken 用户token，邮箱激活码
     * @Column(name="zu_token", type="string", length=128)
     */
    private $zuToken;

    /**
     * @var int $zuTokenTime 邮箱激活码有效期
     * @Column(name="zu_token_time", type="bigint")
     */
    private $zuTokenTime;

    /**
     * @var int $zuStatus 账号状态，0未激活，1已激活
     * @Column(name="zu_status", type="tinyint", default=0)
     */
    private $zuStatus;

    /**
     * @var string $zuUserfrom 第三方登录平台名称
     * @Column(name="zu_userfrom", type="string", length=32)
     */
    private $zuUserfrom;

    /**
     * @var string $zuUserId 从第三方平台获取的ID
     * @Column(name="zu_userId", type="string", length=64)
     */
    private $zuUserId;

    /**
     * @var string $zuEmail 邮件
     * @Column(name="zu_email", type="string", length=255)
     */
    private $zuEmail;

    /**
     * @var string $zuPhone 电话
     * @Column(name="zu_phone", type="string", length=32)
     */
    private $zuPhone;

    /**
     * @var string $zuInter 大洲(二字码)
     * @Column(name="zu_inter", type="string", length=5)
     */
    private $zuInter;

    /**
     * @var string $zuCountry 国家
     * @Column(name="zu_country", type="string", length=32)
     */
    private $zuCountry;

    /**
     * @var int $zuRegtime 用户注册时间
     * @Column(name="zu_regtime", type="bigint")
     */
    private $zuRegtime;

    /**
     * @param int $value
     * @return $this
     */
    public function setZuId(int $value)
    {
        $this->zuId = $value;

        return $this;
    }

    /**
     * 用户名/第三方平台名称
     * @param string $value
     * @return $this
     */
    public function setZuUsername(string $value): self
    {
        $this->zuUsername = $value;

        return $this;
    }

    /**
     * 头像
     * @param string $value
     * @return $this
     */
    public function setZuAvatar(string $value): self
    {
        $this->zuAvatar = $value;

        return $this;
    }

    /**
     * 性别 1男性， 0女性
     * @param int $value
     * @return $this
     */
    public function setZuSex(int $value): self
    {
        $this->zuSex = $value;

        return $this;
    }

    /**
     * 密码
     * @param string $value
     * @return $this
     */
    public function setZuPassword(string $value): self
    {
        $this->zuPassword = $value;

        return $this;
    }

    /**
     * 第三方登录生成的密匙
     * @param string $value
     * @return $this
     */
    public function setZuAuthkey(string $value): self
    {
        $this->zuAuthkey = $value;

        return $this;
    }

    /**
     * 用户token，邮箱激活码
     * @param string $value
     * @return $this
     */
    public function setZuToken(string $value): self
    {
        $this->zuToken = $value;

        return $this;
    }

    /**
     * 邮箱激活码有效期
     * @param int $value
     * @return $this
     */
    public function setZuTokenTime(int $value): self
    {
        $this->zuTokenTime = $value;

        return $this;
    }

    /**
     * 账号状态，0未激活，1已激活
     * @param int $value
     * @return $this
     */
    public function setZuStatus(int $value): self
    {
        $this->zuStatus = $value;

        return $this;
    }

    /**
     * 第三方登录平台名称
     * @param string $value
     * @return $this
     */
    public function setZuUserfrom(string $value): self
    {
        $this->zuUserfrom = $value;

        return $this;
    }

    /**
     * 从第三方平台获取的ID
     * @param string $value
     * @return $this
     */
    public function setZuUserId(string $value): self
    {
        $this->zuUserId = $value;

        return $this;
    }

    /**
     * 邮件
     * @param string $value
     * @return $this
     */
    public function setZuEmail(string $value): self
    {
        $this->zuEmail = $value;

        return $this;
    }

    /**
     * 电话
     * @param string $value
     * @return $this
     */
    public function setZuPhone(string $value): self
    {
        $this->zuPhone = $value;

        return $this;
    }

    /**
     * 大洲(二字码)
     * @param string $value
     * @return $this
     */
    public function setZuInter(string $value): self
    {
        $this->zuInter = $value;

        return $this;
    }

    /**
     * 国家
     * @param string $value
     * @return $this
     */
    public function setZuCountry(string $value): self
    {
        $this->zuCountry = $value;

        return $this;
    }

    /**
     * 用户注册时间
     * @param int $value
     * @return $this
     */
    public function setZuRegtime(int $value): self
    {
        $this->zuRegtime = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getZuId()
    {
        return $this->zuId;
    }

    /**
     * 用户名/第三方平台名称
     * @return string
     */
    public function getZuUsername()
    {
        return $this->zuUsername;
    }

    /**
     * 头像
     * @return string
     */
    public function getZuAvatar()
    {
        return $this->zuAvatar;
    }

    /**
     * 性别 1男性， 0女性
     * @return int
     */
    public function getZuSex()
    {
        return $this->zuSex;
    }

    /**
     * 密码
     * @return string
     */
    public function getZuPassword()
    {
        return $this->zuPassword;
    }

    /**
     * 第三方登录生成的密匙
     * @return string
     */
    public function getZuAuthkey()
    {
        return $this->zuAuthkey;
    }

    /**
     * 用户token，邮箱激活码
     * @return string
     */
    public function getZuToken()
    {
        return $this->zuToken;
    }

    /**
     * 邮箱激活码有效期
     * @return int
     */
    public function getZuTokenTime()
    {
        return $this->zuTokenTime;
    }

    /**
     * 账号状态，0未激活，1已激活
     * @return int
     */
    public function getZuStatus()
    {
        return $this->zuStatus;
    }

    /**
     * 第三方登录平台名称
     * @return string
     */
    public function getZuUserfrom()
    {
        return $this->zuUserfrom;
    }

    /**
     * 从第三方平台获取的ID
     * @return string
     */
    public function getZuUserId()
    {
        return $this->zuUserId;
    }

    /**
     * 邮件
     * @return string
     */
    public function getZuEmail()
    {
        return $this->zuEmail;
    }

    /**
     * 电话
     * @return string
     */
    public function getZuPhone()
    {
        return $this->zuPhone;
    }

    /**
     * 大洲(二字码)
     * @return string
     */
    public function getZuInter()
    {
        return $this->zuInter;
    }

    /**
     * 国家
     * @return string
     */
    public function getZuCountry()
    {
        return $this->zuCountry;
    }

    /**
     * 用户注册时间
     * @return int
     */
    public function getZuRegtime()
    {
        return $this->zuRegtime;
    }

}
