<?php

namespace AwesomeCoder\Contracts\Mail;

interface Attachable
{
    /**
     * Get an attachment instance for this entity.
     *
     * @return \AwesomeCoder\Mail\Attachment
     */
    public function toMailAttachment();
}
