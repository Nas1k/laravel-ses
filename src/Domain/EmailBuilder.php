<?php

namespace Nas1k\LaravelSes\Domain;

use Nas1k\LaravelSes\Domain\Email\MissedRequiredParameterException;

class EmailBuilder implements \Serializable, \JsonSerializable
{
    const DESTINATION_TO  = 'ToAddresses';
    const DESTINATION_CC  = 'CcAddresses';
    const DESTINATION_BCC = 'BccAddresses';

    const MESSAGE_HTML = 'Html';
    const MESSAGE_TEXT = 'Text';

    protected $source;
    protected $destination;
    protected $message;

    public function setSource(string ...$address)
    {
        $this->source = implode(',', $address);
        return $this;
    }

    public function setDestinationTo(string ...$address)
    {
        return $this->setDestination($address, self::DESTINATION_TO);
    }

    public function setDestinationCc(string ...$address)
    {
        return $this->setDestination($address, self::DESTINATION_CC);
    }

    public function setDestinationBcc(string ...$address)
    {
        return $this->setDestination($address, self::DESTINATION_BCC);
    }

    protected function setDestination(array $address, string $type)
    {
        $this->destination[$type] = $address;
        return $this;
    }

    public function setMessageHtml(string $subject, string $body)
    {
        return $this->setMessage($subject, $body, self::MESSAGE_HTML);
    }

    public function setMessageText(string $subject, string $body)
    {
        return $this->setMessage($subject, $body, self::MESSAGE_TEXT);
    }

    protected function setMessage(string $subject, string $body, string $type=self::MESSAGE_TEXT)
    {
        $this->message = [
            'Subject' => ['Data' => $subject],
            'Body'    => [
                $type => ['Data' => $body],
            ],
        ];
        return $this;
    }

    public function build()
    {
        $this->validate();
        return [
            'Source' => $this->source,
            'Destination' => $this->destination,
            'Message' => $this->message,
        ];
    }

    protected function validate()
    {
        if (!$this->source) {
            throw new MissedRequiredParameterException('Source is required field');
        }

        if (!($this->destination && isset($this->destination['ToAddresses']))) {
            throw new MissedRequiredParameterException('Destination is required field');
        }

        if (!$this->message) {
            throw new MissedRequiredParameterException('Message is required field');
        }
    }

    public function serialize()
    {
        return serialize(
            [
                'message' => $this->message,
                'source' => $this->source,
                'destination' => $this->destination,
            ]
        );
    }

    public function unserialize($data)
    {
        $result = unserialize($data);
        $this->message = $result['message'];
        $this->source = $result['source'];
        $this->destination = $result['destination'];
    }

    public function jsonSerialize()
    {
        return $this->build();
    }
}
