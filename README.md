# line-to-discord-bot

LINEグループのトークをDiscordのチャンネルに飛ばすやつ

必要なもの
- Discord Webhook API のURL
- LINE Messaging API のトークンなど
- [line-bot-sdk-php](https://github.com/line/line-bot-sdk-php) (Composerでインストール)
- 秘伝のタレ
- 赤点を回避できる自信


# インストール

```bash
composer install
cp config.sample.php config.php
```

んで `config.php` にいい感じにトークン突っ込んで、Nginxとかで適当にpublic見れるようにしてWebhookの受け取り先に指定すれば良いよ

# 開発ガイド

ngrokとPHPを用意して、

```bash
php -S localhost:10213 -t public/
ngrok http 10213
```

みたいな感じにすればいい感じにねるねるねるねが完成する