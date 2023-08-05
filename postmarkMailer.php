<?php
/**
 * postmarkMailer: sends emails using Posmark API
 *
 * @author Antoni Sobkowicz
 * @copyright 2023 Antoni Sobkowicz
 * @license MIT
 * @version 1.0.0
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * The MIT License
 */
class postmarkMailer extends PluginBase
{
    static protected $name = 'postmarkMailer';
    /** @inheritdoc **/
    protected static $description = 'Send emails using Postmark.';
    /** @inheritdoc, this plugin didn't have any public method */
    public $allowedPublicMethods = array();

    /** @inheritdoc **/
    protected $storage = 'DbStorage';
    /** @inheritdoc **/
    protected $settings = array(
        'postmarkApiKey' => array(
            'type' => 'string',
            'label' => 'Postmark API Key',
        ),
        'postmarkFromEmail' => array(
            'type' => 'string',
            'label' => 'Postmark From Email',
        ),
        'postmarkMessageStream' => array(
            'type'=>'select',
            'label'=>'Message stream',
            'options'=>array(
                'broadcast'=>'Broadcast',
                'outbound'=>'Transactional (Outbound)',
            ),
            'help'=>'Message stream to use for sending emails.',
            'default'=>'broadcast',
        )
    );

    
    public function init()
    {
        $this->subscribe('beforeEmail','beforeEmail');
        $this->subscribe('beforeSurveyEmail','beforeEmail');
        $this->subscribe('beforeTokenEmail','beforeEmail');
    }

    /**
     * Set event send to false when sending an email to example.(com|org)
     * @link https://manual.limesurvey.org/BeforeTokenEmail
     */
    public function beforeEmail()
    {
        $emailTos=$this->getEvent()->get("to");
        $emailSubject=$this->getEvent()->get("subject");
        $emailBody=$this->getEvent()->get("body");
        $emailFrom=$this->getEvent()->get("from");

        if (empty($this->get('postmarkApiKey'))) {
            $this->event->set("send",true);        
            $this->event->set("error",true);    
            $this->event->set("message","Postmark API Key is not set.");
            return;
        }
        
        if (empty($this->get('postmarkFromEmail'))) {
            $this->event->set("send",true);        
            $this->event->set("error",true);    
            $this->event->set("message","Postmark From Email is not set.");
            return;
        }
        
        foreach ($emailTos as $email) {
            $apiUrl = 'https://api.postmarkapp.com/email';
            $apiKey = $this->get('postmarkApiKey');

            $data = array(
                'From' =>  $this->get('postmarkFromEmail'),
                'To' => $email[0],
                'Subject' => $emailSubject,
                'HtmlBody' => $emailBody,
                'MessageStream' => $this->get('postmarkMessageStream')
            );

            $payload = json_encode($data);

            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Accept: application/json',
                'Content-Type: application/json',
                'X-Postmark-Server-Token: ' . $apiKey
            ));

            $response = curl_exec($ch);
            $responseData = json_decode($response, true);
            if (isset($responseData['ErrorCode']) && $responseData['ErrorCode'] !== 0) {
                $this->event->set("send",true);        
                $this->event->set("error",true);    
                $this->event->set("message",$responseData['Message']);
                curl_close($ch);
                return;
            }      

            if (curl_errno($ch)) {
                $this->event->set("send",true);        
                $this->event->set("error",true);    
                $this->event->set("message",$ch);
                curl_close($ch);
                return;
            }

            curl_close($ch);
        }
        $this->event->set("send",false);
    }

}
