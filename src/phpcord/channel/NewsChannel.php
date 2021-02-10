<?php

namespace phpcord\channel;

class NewsChannel extends TextChannel {
	/**
	 * Returns channel type, News in this case
	 *
	 * @api
	 *
	 * @return ChannelType
	 */
	public function getType(): ChannelType {
		return new ChannelType(ChannelType::TYPE_TEXT);
	}
}


