---
title: 'Webhooks Overview'
menuTitle: 'Overview'
description: ''
category: 'Webhooks'
fullscreen: false 
position: 60
---

Telegram bots can interact with chats and users through a webhook system that enables it to be updated about chats changes, new commands and user interactions without continuously polling Telegram APIs for updates.

<alert type="alert">By default webhooks can handle incoming requests from "known" chats (the one stored in database as TelegraphChat models) and will reject all others. In order to handle unknown chats see [below](webhooks/overview#handle-requests-from-unknown-chats)</alert>


## Default Handler

A default "do nothing" handler is shipped with Telegraph installation, it can only handle a single chat command:

```
/chatid
```

And answers with the ID of the chat the command is issued into. It is useful to get the ChatID in order to register a new chat in Telegraph


## Custom Handler

In order to write custom webhook and commands handlers the default handler must be switched with a custom one

```php
// app/Http/Webhooks/MyWebhookHandler.php

class MyWebhookHandler extends \DefStudio\Telegraph\Handlers\WebhookHandler
{
    public function myCustomHandler(): void
    {
        // ... My awesome code
    }
}
```

<alert type="alert">A custom webhook handler must extend `DefStudio\Telegraph\Handlers\WebhookHandler` and has to be registered in `config('telegraph.webhook_handler')`</alert>

A detailed description of how WebhookHandlers work can be found in the next sections

## Handle requests from unknown chats

By default webhooks can handle incoming requests from "known" chats (the one stored in database as TelegraphChat models) and will reject all others.

Callback queries, commands and messages handling from unknown chats can be enabled from telegraph config in security settings:

```php
 'security' => [
    /*
     * if enabled, allows callback queries from unregistered chats
     */
    'allow_callback_queries_from_unknown_chats' => true,

    /*
     * if enabled, allows messages and commands from unregistered chats
     */
    'allow_messages_from_unknown_chats' => true,
    
     /*
     * if enabled, store unknown chats as new TelegraphChat models
     */
    'store_unknown_chats_in_db' => true,
],
```
