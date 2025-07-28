<?php

namespace App\Helpers;

abstract class Format {
	
	public static function formatDate($date) {
		return date('d/m/Y', strtotime($date));
	}

	public static function formatDateTime($date) {
		return date('d/m/Y H:i:s', strtotime($date));
	}

	public static function formatDateToDB($date) {
		if ($d = date_create_from_format('d/m/Y', $date)) {
			return $d->format('Y-m-d');
		}

		if($d = date_create_from_format('d/m/Y H:i', $date)) {
			return $d->format('Y-m-d H:i');
		}

		return $date;
	}

	/**
	 * Formata uma string para um formato do CEP
	 *
	 * @param  string $number
	 * @return string
	 */
	public static function cep($number) {
		// Remove o que não for número
		$number = preg_replace('/\D/', '', $number);

		// Nenhum formato conhecido
		if (strlen($number) != 8) {
			return $number;
		}

		return preg_replace('/(\d{5})(\d*)/', '$1-$2', $number);
	}

	/**
	 * Coloca a string na formatação de CPF
	 *
	 * @param  string $number
	 * @return string
	 */
	public static function cpf($number) {
		return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $number);
	}
}