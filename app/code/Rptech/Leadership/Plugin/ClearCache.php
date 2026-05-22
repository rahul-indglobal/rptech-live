<?php

namespace Rptech\Leadership\Plugin;

use Rptech\Leadership\Model\ResourceModel\Leadership as LeadershipResource;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Model\AbstractModel;
use Rptech\Leadership\Model\Leadership;

class ClearCache
{
    /**
     * Application Cache Manager
     *
     * @var CacheInterface
     */
    protected $cacheManager;

    /**
     * ClearCache constructor.
     * @param CacheInterface $cacheManager
     */
    public function __construct(
        CacheInterface $cacheManager
    ) {
        $this->cacheManager = $cacheManager;
    }

    /**
     * @param LeadershipResource $subject
     * @param $result
     * @return LeadershipResource
     */
    public function afterSave(LeadershipResource $subject, $result): LeadershipResource
    {
        $this->cleanCache();
        return $result;
    }

    public function afterDelete(LeadershipResource $subject, $result, AbstractModel $brand): LeadershipResource
    {
        $this->cleanCache();
        return $result;
    }

    private function cleanCache()
    {
        $tags = ['LEADERSHIP'];
        $this->cacheManager->clean($tags);
    }
}
