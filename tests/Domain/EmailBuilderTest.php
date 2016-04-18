<?php

namespace Nas1k\LaravelSes\Test\Domain;

use Nas1k\LaravelSes\Domain\EmailBuilder;

class EmailBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testBuild()
    {
        $source = 'source@test.com';
        $destination = 'destination@test.com';
        $cc = 'cc@test.com';
        $bcc = 'bcc@test.com';
        $subject = 'subject';
        $messageTxt = 'test msg';
        $messageHtml = 'test html';

        $emailBuilder = new EmailBuilder();
        $emailBuilder->setSource($source)
            ->setDestinationTo($destination)
            ->setDestinationCc($cc)
            ->setDestinationBcc($bcc)
            ->setMessageHtml($subject, $messageHtml);

        $result = $emailBuilder->build();
        $this->assertArrayHasKey('Source',$result);
        $this->assertArrayHasKey('Destination',$result);
        $this->assertArrayHasKey('Message',$result);
        $this->assertEquals($source, $result['Source']);
        $this->assertArrayHasKey(EmailBuilder::DESTINATION_TO, $result['Destination']);
        $this->assertArrayHasKey(EmailBuilder::DESTINATION_CC, $result['Destination']);
        $this->assertArrayHasKey(EmailBuilder::DESTINATION_BCC, $result['Destination']);
        $this->assertEquals([$destination], $result['Destination'][EmailBuilder::DESTINATION_TO]);
        $this->assertEquals([$cc], $result['Destination'][EmailBuilder::DESTINATION_CC]);
        $this->assertEquals([$bcc], $result['Destination'][EmailBuilder::DESTINATION_BCC]);
        $this->assertArrayHasKey('Subject', $result['Message']);
        $this->assertEquals(['Data' => $subject], $result['Message']['Subject']);
        $this->assertArrayHasKey('Body', $result['Message']);
        $this->assertArrayHasKey(EmailBuilder::MESSAGE_HTML, $result['Message']['Body']);
        $this->assertEquals(['Data' => $messageHtml], $result['Message']['Body'][EmailBuilder::MESSAGE_HTML]);

        $emailBuilder->setMessageText($subject, $messageTxt);
        $result = $emailBuilder->build();
        $this->assertEquals(['Data' => $messageTxt], $result['Message']['Body'][EmailBuilder::MESSAGE_TEXT]);
    }

    /**
     * @expectedException \Nas1k\LaravelSes\Domain\Email\MissedRequiredParameterException
     */
    public function testMissSource()
    {
        (new EmailBuilder())->build();
    }

    /**
     * @expectedException \Nas1k\LaravelSes\Domain\Email\MissedRequiredParameterException
     */
    public function testMissDestination()
    {
        (new EmailBuilder())->setSource('source@test.com')
            ->build();
    }

    /**
     * @expectedException \Nas1k\LaravelSes\Domain\Email\MissedRequiredParameterException
     */
    public function testMissMessage()
    {
        (new EmailBuilder())->setSource('source@test.com')
            ->setDestinationTo('destination@test.com')
            ->build();
    }

    public function testSerializeAndUnserialize()
    {
        $source = 'source@test.com';
        $destination = 'destination@test.com';
        $subject = 'subject';
        $messageTxt = 'test msg';

        $builder = new EmailBuilder();
        $builder->setSource($source)
            ->setDestinationTo($destination)
            ->setMessageText($subject, $messageTxt);
        $serialize = serialize($builder);
        $deserializeBuilder = unserialize($serialize);
        
        $this->assertEquals(
            $deserializeBuilder->build(),
            [
                'Source' => $source,
                'Destination' => [EmailBuilder::DESTINATION_TO => [$destination]],
                'Message' => [
                    'Subject' => ['Data' => $subject],
                    'Body' => [
                        EmailBuilder::MESSAGE_TEXT => ['Data' => $messageTxt]
                    ]
                ]
            ]
        );
    }
}
