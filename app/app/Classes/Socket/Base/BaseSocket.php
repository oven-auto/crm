<?php

namespace App\Classes\Socket\Base;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

Class BaseSocket implements MessageComponentInterface
{

	/**
	 * Событие когда происходит новое подключение
	 *
	 * @param ConnectionInterface $conn The socket/connection that just connected to your application
	 * @return mixed
	 */
	public function onOpen(ConnectionInterface $conn) {
	}

	/**
	 * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
	 *
	 * @param ConnectionInterface $conn The socket/connection that is closing/closed
	 * @return mixed
	 */
	public function onClose(ConnectionInterface $conn) {
	}

	/**
	 * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
	 * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
	 *
	 * @param ConnectionInterface $conn
	 * @param \Exception $e
	 * @return mixed
	 */
	public function onError(ConnectionInterface $conn, \Exception $e) {
	}

	/**
	 * Событие когда сообщение приходит с клиента на сервер
	 *
	 * @param ConnectionInterface $from The socket/connection that sent the message to your application
	 * @param string $msg The message received
	 * @return mixed
	 */
	public function onMessage(ConnectionInterface $from, $msg) {
	}
}
