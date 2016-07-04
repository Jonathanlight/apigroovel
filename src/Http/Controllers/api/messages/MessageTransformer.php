<?php
namespace Groovel\Restapi\Http\Controllers\api\messages;

use Groovel\Restapi\Http\Controllers\api\messages;
use League\Fractal\TransformerAbstract;

class MessageTransformer extends TransformerAbstract
{
	public function transform($message)
	{
		return [
				'id'        => (int) $message->id,
				'subject'        =>  ucfirst($message->subject),
				'author'      => ucfirst($message->author),
				'recipient'     => ucfirst($message->recipient),
				'body'    => ucfirst($message->body),
		];
	}
}