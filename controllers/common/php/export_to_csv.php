<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


    use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpFoundation\ResponseHeaderBag;

    $response = new Response();

	$csv = NULL;

	if (!empty($_POST['data']) && !empty($_POST['filename']))
	{
		// En-tête
		if (isset($_POST['header']))
		{
			$header = json_decode($_POST['header']);
			$csv_h = NULL;
			foreach ($header as $h)
			{
				$h = str_replace('"','""',$h);
				$h = preg_replace( "/\r|\n/", " ", $h);
				if (isset($csv_h)) $csv_h .= ',';
				$csv_h .= '"'.$h.'"';
			}
		}
		if (isset($csv_h)) $csv = $csv_h."\r\n";

		// Corps
		$data = json_decode($_POST['data']);
		foreach ($data as $line)
		{
			$csv_l = NULL;
			foreach ($line as $cell)
			{
				$cell = str_replace('"','""',$cell); // double quotes
				$cell = preg_replace( "/\r|\n/", " ", $cell); // replace line break with space
				if (isset($csv_l)) $csv_l .= ','; // field delimiter
				$csv_l .= '"'.$cell.'"';
			}
			$csv .= $csv_l."\r\n";
		}

		$d = $response->headers->makeDisposition(
		    ResponseHeaderBag::DISPOSITION_ATTACHMENT,
		    $request->request->get('filename').'.csv'
		);

		$response->headers->set('Content-Disposition', $d);
		$response->headers->set('Content-Type', 'application/vnd.ms-excel');
		$response->setContent($csv);
		$response->send();
		die();
	}
