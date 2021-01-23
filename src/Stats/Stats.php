<?php
namespace ServerStats;
class PrintSomething
{
    public static function helloWorld()
    {
        return 'Hello Composer!';
		}
		
		public static function stats()
		{
			$timeout = "1";

			$uptime = @exec('uptime');
			$uptime_data = explode(' up ', $uptime);
			$uptime_data = explode(',', $uptime_data[1]);
			$uptime = $uptime[0].', '.$uptime[1];

			$uptime_data[0] = \str_replace(['days', 'day'], ['g', 'g'], $uptime_data[0]);
			$uptime_data[1] = \ltrim($uptime_data[1]);
			$uptime_data[2] = \ltrim(\str_replace(['users', 'user'], ['', ''], $uptime_data[2]));
			#$uptime_data[3] = \str_replace(['  load average: ', ' load averages: '], ['', ''], $uptime_data[3]);

			$services = array();
			$services[] = array("port" => "80",     "service" => "Web server",          "ip" => "176.227.172.103");
			$services[] = array("port" => "21",     "service" => "FTP",                 "ip" => "");
			$services[] = array("port" => "3306",   "service" => "MYSQL",               "ip" => "");
			$services[] = array("port" => "22",     "service" => "Open SSH",            "ip" => "");
			$services[] = array("port" => "80",     "service" => "Internet Connection", "ip" => "google.com");
			$services[] = array("port" => "443",    "service" => "Web Server HTTPS",    "ip" => "176.227.172.103");
			$services[] = array("port" => "8443",   "service" => "Plesk Panel",         "ip" => "");

			foreach ($services  as $service) {
				if($service['ip']==""){
					$service['ip'] = "localhost";
				}

				$fp = @fsockopen($service['ip'], $service['port'], $errno, $errstr, $timeout);
				if (!$fp) {
					$service['check'] = false;
				} else {
					$service['check'] = true;
				}
				fclose($fp);
			}

			return [
				'uptime' => $uptime,
				'uptime_data' => $uptime_data,
				'services' => $services
			];

		}
}