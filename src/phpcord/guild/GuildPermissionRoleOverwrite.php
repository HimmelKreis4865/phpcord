<?php

namespace phpcord\guild;

class GuildPermissionRoleOverwrite extends GuildPermissionOverwrite {
	/**
	 * Returns the type (0 => guild in this case)
	 *
	 * @internal
	 *
	 * @return int
	 */
	public function getType(): int {
		return GuildPermissionOverwrite::TYPE_ROLE;
	}
}