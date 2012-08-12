<?php
/*
 * This file is part of JenkinsAPI <http://www.contex.me/>.
 *
 * JenkinsAPI is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * JenkinsAPI is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
 
//Example URL: http://ci.contex.me/job/NewProject/1200/
if (isset($_GET['url'])) {
	$build = new JenkinsBuild($_GET['url']);
	echo $build->getNumber();
}

class JenkinsBuild {
	private $data;
	
	public function __construct($request) {
		$api_string = "api/json?pretty=true";
		if (filter_var($request . $api_string, FILTER_VALIDATE_URL) !== false) {
			$this->parseURL($request . $api_string);
		}
	}
	
	protected function parseURL($url) {
		$contents = file_get_contents($url); 
		$contents = utf8_encode($contents); 
		$results = json_decode($contents, true); 
		if (isset($results['result'])) {
			$this->data = $results;
		}
	}
	
	public function getData() {
		return $this->data;
	}
	
	public function getSHA1Hash() {
		return $this->data['actions'][1]['lastBuiltRevision']['SHA1'];
	}
	
	public function getCause() {
		return new JenkinsCause($this->data['actions'][0]['causes'][0]);
	}
	
	public function getArtifacts() {
		$artifacts = array(); 
		foreach ($this->data['artifacts'] as $array) {
			$artifacts[] = new JenkinsArtifact($array);
		}
		return $artifacts;
	}
	
	public function getDescription() {
		return $this->data['description'];
	}
	
	public function getDuration() {
		return $this->data['duration'];
	}
	
	public function getEstimatedUrdation() {
		return $this->data['estimatedDuration'];
	}
	
	public function getFullDisplayName() {
		return $this->data['fullDisplayName'];
	}
	
	public function getID() {
		return $this->data['id'];
	}
	
	public function getKeepLog() {
		return $this->data['keepLog'];
	}
	
	public function getNumber() {
		return $this->data['number'];
	}
	
	public function getResult() {
		return $this->data['result'];
	}
	
	public function getTimestamp() {
		return $this->data['timestamp'];
	}
	
	public function getURL() {
		return $this->data['url'];
	}
	
	public function getBuiltOn() {
		return $this->data['builtOn'];
	}
	
	//TODO: getChangeset, getCulprits, getMavenArtifacts, getMavenVersion
}

class JenkinsCause {
	private $data;
	
	public function __construct($data) {
		$this->data = $data;
	}
	
	public function getData() {
		return $this->data;
	}
	
	public function getShortDescription() {
		return $this->data['shortDescription'];
	}
	
	public function getUserID() {
		return $this->data['userId'];
	}
	
	public function getUsername() {
		return $this->data['userName'];
	}
	
	public function getUpstreamBuild() {
		return $this->data['upstreamBuild'];
	}
	
	public function getUpstreamProject() {
		return $this->data['upstreamProject'];
	}
	
	public function getUpstreamURL() {
		return $this->data['upstreamUrl'];
	}
}

class JenkinsArtifact {
	private $data;
	
	public function __construct($data) {
		$this->data = $data;
	}
	
	public function getData() {
		return $this->data;
	}
	
	public function getDisplayPath() {
		$this->data['displayPath'];
	}
	
	public function getFileName() {
		$this->data['fileName'];
	}
	
	public function getRelativePath() {
		$this->data['relativePath'];
	}
}
?>