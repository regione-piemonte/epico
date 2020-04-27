<?php
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/

//
// +---------------------------------------------------------------------------+
// | UseCase   Ver. 1.00                       PHP 5                           |
// +---------------------------------------------------------------------------+
// | Copyright (©) 2007 GM                                         |
// +---------------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or             |
// | modify it under the same terms as Perl itself.                            |
// |                                                                           |
// | Permission granted to use and modify this library so long as the          |
// | copyright above is maintained, modifications are documented, and          |
// | credit is given for any use of the library.                               |
// +---------------------------------------------------------------------------+
// | Author:GM/Alessandro Battezzati                            |
// +---------------------------------------------------------------------------+
//

class UseCase {

	private $appId;
	private $id;


	public function UseCase(array $useCaseHashMap) {
		$this->appId = $useCaseHashMap['appId'];
		$this->id = $useCaseHashMap['id'];
	}


	public function setAppId($appId) {
		$this->appId = $appId;
	}

	public function getAppId() {
		return $this->appId;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getId() {
		return $this->id;
	}

	public function toHashMap() {
		return array('appId' => $this->appId,
			'id' => $this->id);
	}

}

?>