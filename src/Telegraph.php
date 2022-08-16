<?php

/** @noinspection PhpUnused */
/** @noinspection PhpDocMissingThrowsInspection */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph;

use DefStudio\Telegraph\Client\TelegraphResponse;
use DefStudio\Telegraph\Concerns\AnswersInlineQueries;
use DefStudio\Telegraph\Concerns\CallTraitsMethods;
use DefStudio\Telegraph\Concerns\ComposesMessages;
use DefStudio\Telegraph\Concerns\HasBotsAndChats;
use DefStudio\Telegraph\Concerns\InteractsWithTelegram;
use DefStudio\Telegraph\Concerns\InteractsWithWebhooks;
use DefStudio\Telegraph\Concerns\ManagesKeyboards;
use DefStudio\Telegraph\Concerns\SendsAttachments;
use DefStudio\Telegraph\Concerns\StoresFiles;
use DefStudio\Telegraph\DTO\Attachment;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Collection;

class Telegraph
{
    use CallTraitsMethods;
    use InteractsWithTelegram;
    use HasBotsAndChats;
    use ComposesMessages;
    use ManagesKeyboards;
    use InteractsWithWebhooks;
    use SendsAttachments;
    use StoresFiles;
    use AnswersInlineQueries;

    public const MAX_DOCUMENT_SIZE_IN_MB = 50;
    public const MAX_PHOTO_SIZE_IN_MB = 10;
    public const MAX_PHOTO_HEIGHT_WIDTH_TOTAL = 10000;
    public const MAX_PHOTO_HEIGHT_WIDTH_RATIO = 20;
    public const MAX_THUMBNAIL_SIZE_IN_KB = 200;
    public const MAX_THUMBNAIL_HEIGHT = 320;
    public const MAX_THUMBNAIL_WIDTH = 320;
    public const ALLOWED_THUMBNAIL_TYPES = ['jpg'];


    public const PARSE_HTML = 'html';
    public const PARSE_MARKDOWN = 'markdown';

    protected const TELEGRAM_API_BASE_URL = 'https://api.telegram.org/bot';
    protected const TELEGRAM_API_FILE_BASE_URL = 'https://api.telegram.org/file/bot';

    public const ENDPOINT_GET_BOT_UPDATES = 'getUpdates';
    public const ENDPOINT_GET_BOT_INFO = 'getMe';
    public const ENDPOINT_REGISTER_BOT_COMMANDS = 'setMyCommands';
    public const ENDPOINT_UNREGISTER_BOT_COMMANDS = 'deleteMyCommands';
    public const ENDPOINT_SET_WEBHOOK = 'setWebhook';
    public const ENDPOINT_UNSET_WEBHOOK = 'deleteWebhook';
    public const ENDPOINT_GET_WEBHOOK_DEBUG_INFO = 'getWebhookInfo';
    public const ENDPOINT_ANSWER_WEBHOOK = 'answerCallbackQuery';
    public const ENDPOINT_REPLACE_KEYBOARD = 'editMessageReplyMarkup';
    public const ENDPOINT_MESSAGE = 'sendMessage';
    public const ENDPOINT_DELETE_MESSAGE = 'deleteMessage';
    public const ENDPOINT_PIN_MESSAGE = 'pinChatMessage';
    public const ENDPOINT_UNPIN_MESSAGE = 'unpinChatMessage';
    public const ENDPOINT_UNPIN_ALL_MESSAGES = 'unpinAllChatMessages';
    public const ENDPOINT_EDIT_MESSAGE = 'editMessageText';
    public const ENDPOINT_EDIT_CAPTION = 'editMessageCaption';
    public const ENDPOINT_SEND_LOCATION = 'sendLocation';
    public const ENDPOINT_SEND_VOICE = 'sendVoice';
    public const ENDPOINT_SEND_CHAT_ACTION = 'sendChatAction';
    public const ENDPOINT_SEND_DOCUMENT = 'sendDocument';
    public const ENDPOINT_SEND_PHOTO = 'sendPhoto';
    public const ENDPOINT_GET_FILE = 'getFile';
    public const ENDPOINT_ANSWER_INLINE_QUERY = 'answerInlineQuery';


    /** @var array<string, mixed> */
    protected array $data = [];

    /** @var Collection<string, Attachment> */
    protected Collection $files;

    public function __construct()
    {
        $this->files = Collection::empty();
    }

    public function send(): TelegraphResponse
    {
        $response = $this->sendRequestToTelegram();

        return TelegraphResponse::fromResponse($response);
    }

    public function dispatch(string $queue = null): PendingDispatch
    {
        return $this->dispatchRequestToTelegram($queue);
    }

    /**
     * @return never-returns
     */
    public function dd(): void
    {
        dd($this->toArray());
    }

    public function dump(): Telegraph
    {
        dump($this->toArray());

        return $this;
    }
}
