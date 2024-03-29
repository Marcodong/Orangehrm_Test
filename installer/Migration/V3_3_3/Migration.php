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

namespace OrangeHRM\Installer\Migration\V3_3_3;

use OrangeHRM\Installer\Util\V1\AbstractMigration;

class Migration extends AbstractMigration
{
    public function up(): void
    {
        $this->execQueries('dbscript-1.sql');
        $this->execQueries('dbscript-2.sql');
    }

    /**
     * @param $fileName
     */
    private function execQueries($fileName): void
    {
        $script = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . $fileName);
        $dbScriptStatements = preg_split('/;\s*$/m', $script);
        foreach ($dbScriptStatements as $statement) {
            if (empty(trim($statement))) {
                continue;
            }
            $this->getConnection()->executeStatement($statement);
        }
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): string
    {
        return '3.3.3';
    }
}
