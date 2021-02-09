<?php

namespace phpcord\guild;

class GuildPermissionMemberOverwrite extends GuildPermissionOverwrite {
	/**
	 * Returns the type (1 => member in this case)
	 *
	 * @internal
	 *
	 * @return int
	 */
	public function getType(): int {
		return GuildPermissionOverwrite::TYPE_MEMBER;
	}
}