<?php 
//Geliştirici: Yücel Kahraman (http://yucelkahraman.com.tr) https://github.com/3zRasasi/PHP-E-Fatura-Imzalama-Araci
//GPLv3 Genel Kamu Lisansı ile lisanslanmıştır. Detaylar için LİSANS dosyasına bakın.

	//Dosya ayarları
	$dizin=(__DIR__ . DIRECTORY_SEPARATOR);  
	$java_dosyasi=$dizin."Java\NTGEFaturaImza.jar";  
	$fatura_dosyasi=$dizin."TemelFaturaOrnegi.xml"; 
	
	//Akıllı Kart şifresi
	$pin='123456';  
	
	//Seri imza olsun mu?
	$seriImza="hayır";
	
         //Dosyaya kaydetsin mi?
	$dosyayaKaydet="evet";

	
        //bir array içerisinde aranan var mı yok mu kontrol et
	function DizideVarMi($aranan, array $dizi)
	{
			foreach ($dizi as $anahtar => $deger) 
			{
				if (false !== stripos($deger, $aranan)) 
				{
				return true;
				}
			}
           return false;
	}
	
	
	/* Akıllı Kart Fatura İmzalama */  
	function Imzala($java_dosyasi,$fatura_dosyasi,$pin=null,$seriImza,$dosyayaKaydet)  
	{  
	
		/* Java 0 - 1 i kabul etmiyor. İlla True - False olacak diyor */ 
		if ($seriImza== "hayır") { $seriImza= "false"; } elseif ($seriImza== "evet") { $seriImza= "true"; }
		if ($dosyayaKaydet== "hayır") { $dosyayaKaydet= "false"; } elseif ($dosyayaKaydet== "evet") { $dosyayaKaydet= "true"; }
	
	
		/* Java Dosyasına Veri Yollanıyor */  
		if($pin!=null)  
		{ 
				$java_komutu= $java_dosyasi." {".$fatura_dosyasi.",".$pin.",".$seriImza.",".$dosyayaKaydet."}";
				exec("java -Dfile.encoding=UTF8 -jar ".$java_komutu." 2>&1",$cikti);    
				
	
						//işlem başarılı mı kontrol et
					        if( DizideVarMi("'java'",$cikti)==true and DizideVarMi("komut",$cikti)==true and DizideVarMi("program ya da toplu",$cikti)==true and DizideVarMi("olarak",$cikti)==true )   //Array ( [0] => 'java' i� ya da d�� komut, �al��t�r�labilir [1] => program ya da toplu i� dosyas� olarak tan�nm�yor. )
						{  
								$islem_sonucu="Java yuklu degil";   
						}
						elseif( DizideVarMi("İmzalandı",$cikti)==true)  
						{  
								$islem_sonucu="İmzalandı";   
						}
						elseif( DizideVarMi("Hatalı PIN",$cikti)==true)  
						{  
								$islem_sonucu="Hatalı PIN";   
						}
						elseif( DizideVarMi("PIN kilitli",$cikti)==true)  
						{  
								$islem_sonucu="PIN Kodu bloke oldu.PUK Kodunu giriniz.";  
						}
						else
						{  
								$islem_sonucu="PUK Kodu bloke oldu";  break;
						}						
					
				  
						return $islem_sonucu;  //işlem sonucunu göster
		}  
	}  
	
	
	
	
	            $islem_sonucu = Imzala($java_dosyasi, $fatura_dosyasi, $pin, $seriImza, $dosyayaKaydet);
		    echo $islem_sonucu;
		
	
?>
