<?php

namespace App\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\EmailBuilder;

/**
 * @ORM\Entity
 * @ORM\Table(name="report")
 */
class Report implements \JsonSerializable
{
    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR = 'error';

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $status;

    /**
     * @var EmailBuilder
     * @ORM\Column(type="text", nullable=false)
     */
    protected $message;

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param EmailBuilder $message
     * @return $this
     */
    public function setMessage(EmailBuilder $message)
    {
        $this->message = serialize($message);
        return $this;
    }

    /**
     * @return EmailBuilder
     */
    public function getMessage()
    {
        return unserialize($this->message);
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status)
    {
        if (!in_array($status, [self::STATUS_ERROR, self::STATUS_SUCCESS])) {
            throw new \InvalidArgumentException('Invalid status');
        }
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'message' => $this->message,
        ];
    }
}
