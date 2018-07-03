<?php
/**
 * Shopware 5
 * Copyright (c) shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

namespace Shopware\Bundle\BenchmarkBundle\Provider;

use Doctrine\DBAL\Connection;
use Shopware\Bundle\BenchmarkBundle\BenchmarkProviderInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;

class ShopProvider implements BenchmarkProviderInterface
{
    /**
     * @var Connection
     */
    private $dbalConnection;

    public function __construct(Connection $dbalConnection)
    {
        $this->dbalConnection = $dbalConnection;
    }

    public function getName()
    {
        return 'shop';
    }

    /**
     * {@inheritdoc}
     */
    public function getBenchmarkData(ShopContextInterface $shopContext)
    {
        $now = new \DateTime('now');

        return [
            'id' => $this->getUniqueShopHash(),
            'industry' => $this->getIndustry(),
            'datetime' => $now->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @return string
     */
    private function getUniqueShopHash()
    {
        $queryBuilder = $this->dbalConnection->createQueryBuilder();

        return $queryBuilder->select('config.id')
            ->from('s_benchmark_config', 'config')
            ->execute()
            ->fetchColumn();
    }

    /**
     * @return string
     */
    private function getIndustry()
    {
        $queryBuilder = $this->dbalConnection->createQueryBuilder();

        return $queryBuilder->select('industry')
            ->from('s_benchmark_config')
            ->execute()
            ->fetchColumn();
    }
}