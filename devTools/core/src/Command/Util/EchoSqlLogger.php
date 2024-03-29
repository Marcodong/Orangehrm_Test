<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software: you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with OrangeHRM.
 * If not, see <https://www.gnu.org/licenses/>.
 */

namespace OrangeHRM\DevTools\Command\Util;

use Doctrine\DBAL\Logging\SQLLogger;

/**
 * @experimental
 */
class EchoSqlLogger implements SQLLogger
{
    private $replace;

    public function __construct($replace = false)
    {
        $this->replace = $replace;
    }

    /**
     * @inheritdoc
     */
    public function startQuery($sql, ?array $params = null, ?array $types = null)
    {
        echo $sql . "\n\n";

        if ($params) {
            echo "Params: \n";
            var_export($params);
            echo "\n\n";
        }

        if (!$types) {
            return;
        }

        echo "Types: \n";
        var_export($types);
        echo "\n\n";
    }

    /**
     * @inheritdoc
     */
    public function stopQuery()
    {
    }
}
