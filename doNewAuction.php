<?php	
class AllegroWebAPI extends SoapClient {
	
	/* ZAMIESZCZONO TYLKO INTERESUJĄCĄ NAS METODĘ 
	$this->sessionHandlePart zostaje ustawiona przy logowaniu
		odrębną metodą klasy (nie ukazana tutaj)	
	*/
	
	
	public final function doNewAuction($aFieldStructure, $iLocalId=null, $bIsPreview=true) {
		# Metoda służy wystawiania nowej oferty w trybie produkcyjnym (argument $bIsPreview = false) lub testowania wystawiania oferty (argument $bIsPreview = true); tryb testowy.
		
		if($bIsPreview) { //tryb testowy
			$result = json_decode(json_encode(parent::doCheckNewAuctionExt(array(
				'sessionHandle' => $this->sessionHandlePart,
				'fields' => $aFieldStructure,
				'localId' => $iLocalId))
			), true);
		}
		else { //tryb produkcyjny
			$result = json_decode(json_encode(parent::doNewAuctionExt(array(
				'sessionHandle' => $this->sessionHandlePart,
				'fields' => $aFieldStructure,
				'localId' => $iLocalId))
			), true);
		}
		return $result;
	}
}
$client = new AllegroWebAPI($stoautConfig);
// już zalogowano i pozyskano uchwyt sesji

// Przetworzono strukturę aukcji i znajduje się ona w $aAuction

var_dump($aAuction); //tablica-struktura jak w pliku action.txt

# Próba wystawienia aukcji
try {	
	$aNewAuction = $client->doNewAuction($aAuction, $item, true); //wersja preview metody
}
catch (SoapFault $soapFault) {
	echo "Request :<br>", $client->getLastRequest(), "<br>"; //wywala zawartość [doCheckNewAuctionExt()]soap_request.xml
	echo "Response :<br>", $client->getLastResponse(), "<br>"; //wywala zawartość [doCheckNewAuctionExt()]soap_response.xml
	echo "doNewAuction(aukcja {$item}): ", $soapFault->faultcode, " &mdash; ", $soapFault->faultstring, EOL;
}
?>
