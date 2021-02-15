<?php

namespace Spatie\RayBundle\Payloads;

use Spatie\Ray\Payloads\Payload;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Throwable;

class EmailPayload extends Payload
{
    /** @var string */
    protected $html = '';

    /** @var Email|null */
    protected $email;

    public static function forEmail(Email $email)
    {
        return new static(self::renderEmail($email), $email);
    }

    public function __construct(string $html, Email $email = null)
    {
        $this->html = $html;

        $this->email = $email;
    }

    public function getType(): string
    {
        return 'mailable';
    }

    public function getContent(): array
    {
        $content = [
            'html' => $this->html,
            'from' => [],
            'to' => [],
            'cc' => [],
            'bcc' => [],
        ];

        if ($this->email) {
            $content = array_merge($content, [
                'mailable_class' => get_class($this->email),
                'from' => $this->convertToPersons($this->email->getFrom()),
                'subject' => $this->email->getSubject(),
                'to' => $this->convertToPersons($this->email->getTo()),
                'cc' => $this->convertToPersons($this->email->getCc()),
                'bcc' => $this->convertToPersons($this->email->getBcc()),
            ]);
        }

        return $content;
    }

    protected static function renderEmail(Email $email): string
    {
        try {
            return (string)$email->getHtmlBody();
        } catch (Throwable $exception) {
            return "Email could not be rendered because {$exception->getMessage()}";
        }
    }

    protected function convertToPersons(array $persons): array
    {
        return array_map(function (Address $address) {
            return [
                'email' => $address->getAddress(),
                'name' => $address->getName(),
            ];
        }, $persons);
    }
}
