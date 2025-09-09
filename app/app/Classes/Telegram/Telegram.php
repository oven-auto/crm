<?php

namespace App\Classes\Telegram;

use Telegram\Bot\Api;
use App\Models\TelegramLongpollingOffset;

class Telegram
{
    public static $instance;

    public $service;

    public $offset;

    private function __construct() {}



    /**
     * ИНИЦИАЛИЗАЦИЯ КЛАССА
     */
    public static function init(): self
    {
        if (self::$instance !== null)
            return self::$instance;

        $me = new self;

        $me->service = new Api(env('TELEGRAM_KEY'));

        $me->offset = TelegramLongpollingOffset::firstOrNew();

        return $me;
    }



    /**
     * ПОЛУЧИТЬ ОФФСЕТ ДЛЯ ЛОНГПУЛИНГА
     */
    private function getOffsetValue(): int|bool
    {
        return $this->offset->value;
    }



    /**
     * ПОСТАВИТЬ НОВЫЙ ОФФСЕТ
     */
    private function setOffset(\Telegram\Bot\Objects\Update|bool $last): void
    {
        if ($last) {
            $this->offset->value = $last->update_id + 1;

            $this->offset->save();
        }
    }



    /**
     * ПОЛУЧИТЬ СООБЩЕНИЯ ЛОНГПУЛИНГА
     */
    public function getUpdates()
    {
        $data = $this->service->getUpdates([
            'offset' => $this->getOffsetValue() ?? '',
        ]);

        $this->setOffset(end($data));

        return $data;
    }



    /**
     * ОТПРАВИТЬ СООБЩЕНИЕ
     */
    public function sendMessage($chatId, $message, $options = [])
    {
        $options['chat_id'] = $chatId;
        
        $options['text'] = $message;
        
        $this->service->sendMessage($options);
    }
}
