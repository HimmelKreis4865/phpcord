<?php

namespace phpcord\channel;

class NewsChannel extends TextChannel {
	public function getType(): ChannelType {
		return new ChannelType(ChannelType::TYPE_TEXT);
	}
}


