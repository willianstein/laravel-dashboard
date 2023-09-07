<?php

namespace App\Models\History;

use App\Models\Histories;
use Illuminate\Database\Eloquent\Model;

class History {

    private static Model $entity;

    private string $description;

    private int $user_id;

    private ?string $notes;

    private string $link;

    private string $linkType;

    private string $message;

    public function __construct(Model $entity) {
        self::$entity = $entity;
    }

    /**
     * DESCRIÇÃO
     * @param string $description
     * @return History
     */
    public function description(string $description): History {
        $this->description = $description;
        return $this;
    }

    /**
     * Id do usuario
     * @param string $user
     * @return History
     */
    public function userId(int $user_id): History {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * OBSERVAÇÕES
     * @param string|null $notes
     * @return History
     */
    public function notes(?string $notes): History {
        $this->notes = $notes;
        return $this;
    }

    /**
     * LINK
     * @param string $link
     * @return History
     */
    public function link(string $link): History {
        $this->link = $link;
        return $this;
    }

    /**
     * TIPO DO LINK
     * @param string $linkType
     * @return History
     */
    public function linkType(string $linkType): History {
        $this->linkType = $linkType;
        return $this;
    }

    /**
     * RETORNA MENSAGEM
     * @return string
     */
    public function getMessage(): string {
        return $this->message;
    }

    /**
     * SALVA HISTÓRICO
     * @return bool
     */
    public function save():bool {
        if(!Histories::create([
            'entity_id'     => self::$entity->id,
            'entity'        => self::$entity::class,
            'description'   => $this->description,
            'notes'         => $this->notes??null,
            'link'          => $this->link??null,
            'link_type'     => $this->linkType??null,
            'user_id'       => $this->user_id??null
        ])){
            $this->message = 'Falha ao adicionar histórico';
            return false;
        }
        return true;
    }





}
