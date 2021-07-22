<?php


namespace App\Jobs;


abstract class AbstractCharacterJob extends EsiBase
{

    protected $character_id;

    public function __construct(int $character_id)
    {
        $this->character_id = $character_id;
    }

    public function getCharacterId(): int
    {
        return $this->character_id;
    }

    public function tags(): array
    {
        $tags = parent::tags();

        if(! in_array('character', $tags))
            $tags[] = 'character';

        if(! in_array($this->getCharacterId(), $tags))
            $tags[] = $this->getCharacterId();

        return $tags;
    }

}
