<?php


namespace TheContentExchange\WpWrappers\WpData;

use TheContentExchange\DTO\TheContentExchangeUser as User;

/**
 * Class WpUserWrapper
 * @package TheContentExchange\WpWrappers\WpData
 */
class TheContentExchangeWpUserWrapper
{
    /**
     * @param int $id
     */
    public function tceGetUserNameById(int $id): string
    {
        return $this->tceGetUserById($id)->display_name;
    }

    /**
     * @param string $userRight
     */
    public function tceCheckIfUserHasRightTo(string $userRight): bool
    {
        return current_user_can($userRight);
    }

    /**
     * @param int $id
     * @return false|User
     */
    private function tceGetUserById(int $id)
    {
        return $this->tceConvertWpUserToUser(get_user_by('id', $id));
    }

    /**
     * @param object $wpUser
     */
    private function tceConvertWpUserToUser(object $wpUser): User
    {
        $user = new User();
        $user->display_name = $wpUser->display_name;

        return $user;
    }
}
