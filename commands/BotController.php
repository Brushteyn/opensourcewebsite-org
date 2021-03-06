<?php

namespace app\commands;

use yii\console\Controller;
use app\modules\bot\models\Bot;

/**
 * Class BotController
 *
 * @package app\commands
 */
class BotController extends Controller
{
    /**
     * Enable all inactive bots
     *
     * @throws \TelegramBot\Api\Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionEnableAll()
    {
        /** @var null|Bot[] $bots */
        $bots = Bot::find()->where(['status' => Bot::BOT_STATUS_DISABLED])->all();
        if ($bots) {
            foreach ($bots as $bot) {
                if ($bot->setWebhook()) {
                    echo "The bot {$bot->name} has been enabled\n";
                }
            }
        } else {
            echo "No inactive bots found.\n";
        }
    }

    public function actionDisableAll()
    {
        /** @var null|Bot[] $bots */
        $bots = Bot::find()->where(['status' => Bot::BOT_STATUS_ENABLED])->all();
        if ($bots) {
            foreach ($bots as $bot) {
                if ($bot->deleteWebhook()) {
                    echo "The bot {$bot->name} has been disabled\n";
                }
            }
        } else {
            echo "No active bots found.\n";
        }
    }

    /**
     * Add new bot
     *
     * @param $name
     * @param $token
     */
    public function actionAdd($name, $token)
    {
        $bot = new Bot();
        $bot->name = $name;
        $bot->token = $token;
        if ($bot->save()) {
            echo "Bot $name has been successfully saved\n";
        } else {
            echo current($bot->getFirstErrors()) . "\n";
        }
    }
}