<?php


namespace App\MessageHandler;


use App\Entity\News;
use App\Message\NewsParse;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class NewsParseHandler implements MessageHandlerInterface
{
    private \Doctrine\Persistence\ObjectManager $em;

    public function __construct(ManagerRegistry $registry)
    {
        $this->em = $registry->getManager();
    }

    public function __invoke(NewsParse $newsParse)
    {
        $feeds = [];

        $i = 0;

        foreach ($newsParse->getLoad()->channel->item as $item)
        {
            preg_match_all('@src="([^"]+)"@', (string)$item->children("content", true)->encoded, $match);
            $parts = explode('<font size="-1">', (string)$item->description);

            $entity = $this->em->getRepository(News::class)->findOneBy(['title'=>(string) $item->title]);
            $time = new \DateTime();
            if (is_null($entity)){
                $entity = new News();
                $entity->setTitle((string) $item->title);
                $entity->setDateAdded($time);
                $entity->setPicture($match[1][3]??$match[1][1]??NULL);
                $entity->setShortDescription($this->shortDescription($parts[0]));
                $this->em->persist($entity);
            }else{
                $entity->setDateUpdated($time);
            }
            $this->em->flush();
            $i++;
        }
    }

    private function shortDescription($summary, $max_len = 100): string
    {
        $summary = strip_tags($summary);

        if (strlen($summary) > $max_len)
            $summary = substr($summary, 0, $max_len) . '...';

        return htmlentities($summary);
    }
}