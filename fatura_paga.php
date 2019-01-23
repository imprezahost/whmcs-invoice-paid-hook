<?php

/************************************************************
* Copyright Impreza Host, Seychelles 2015-2019. (https://impreza.host/)
*
* Developed by Edvan. (https://www.edvan.com.br)
*
* This file is part of the WHMCS HOOK - INVOICE PAID
* File     : fatura_paga.php
* Version  : 1.0
* License  : GPL-v3
* Signature: To protect the integrity of the source code, this program
*            is signed with the code signing key used by the copyright
*            holder, Impreza Host.
* Date     : 01/23/2019
* Contact  : Please send enquiries and bug-reports to support@impreza.email
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
**************************************************************/

if (!defined("WHMCS")) die("This file cannot be accessed directly");

function pedidospagos($vars) {
				
	$invoiceid=$vars[invoiceId];
	$userid=$vars[userId];
				
		$consulta_produto = mysql_fetch_assoc(mysql_query("SELECT tblhosting.packageid AS groupid FROM tblinvoiceitems left join tblhosting on tblhosting.id=tblinvoiceitems.relid left join tblproducts on tblproducts.id=tblhosting.packageid where invoiceid='$invoiceid'"));	
		if(in_array($consulta_produto[groupid],$GLOBALS['fatura_paga_idproduto'])){				
			$consultar_cliente = mysql_fetch_assoc(mysql_query("SELECT firstname, language FROM tblclients WHERE id='$userid'"));
			$firstname=$consultar_cliente[firstname];
			$language=$consultar_cliente[language];
			
			if ($language=="english"){
				$subject='Order Confirmation';
				$message='Hello '.$firstname.'!
				Thank you for your order. This message is to confirm that we received your order and it was sent to our provisioning department.

Our normal provisioning time is 1-4 hours, however, in some cases our provisioning time may be longer. We appreciate your patience and we will deliver your server as soon as possible.

Note: Custom partitioning will require extra time to provision your server (up to 24 hours).

If you are new to Impreza Host, feel free to talk to us whenever you need us, we are here 24 hours to help you.';
			}
			elseif ($language=="spanish"){
				$subject='Confirmación de Pedido';
				$message='¡Hola '.$firstname.'!
				Gracias por su solicitud. Este mensaje es para confirmar que recibimos su pedido y el mismo fue enviado a nuestro departamento de aprovisionamiento.

Nuestro tiempo de aprovisionamiento normal es de 1-4 horas, sin embargo, en algunos casos, nuestro tiempo de aprovisionamiento puede ser mayor. Agradecemos su paciencia y entregaremos su servidor lo más rápido posible.

Observe: el particionamiento personalizado requerirá tiempo extra para aprovisionar su servidor (hasta 24 horas).

Si usted es nuevo en Impreza Host, siéntase cómodo para hablar con nosotros siempre que lo necesite, estamos aquí 24 horas para ayudarle.';
			}
			elseif ($language=="portuguese-br"){
				$subject='Confirmação de Pedido';
				$message='Olá '.$firstname.'!
				Obrigado pelo seu pedido. Esta mensagem é para confirmar que recebemos seu pedido e o mesmo foi enviado para o nosso departamento de provisionamento.

Nosso tempo de provisionamento normal é de 1-4 horas, no entanto, em alguns casos, nosso tempo de provisionamento pode ser maior. Agradecemos a sua paciência e entregaremos o seu servidor o mais rápido possível.

Observe: o particionamento personalizado exigirá tempo extra para provisionar seu servidor (até 24 horas).

Se você é novo na Impreza Host, sinta-se à vontade para falar conosco sempre que precisar, estamos aqui 24 horas para ajudar você.';
			}
			else{
				$subject='Order Confirmation';
				$message='Hello '.$firstname.'!
				Thank you for your order. This message is to confirm that we received your order and it was sent to our provisioning department.

Our normal provisioning time is 1-4 hours, however, in some cases our provisioning time may be longer. We appreciate your patience and we will deliver your server as soon as possible.

Note: Custom partitioning will require extra time to provision your server (up to 24 hours).

If you are new to Impreza Host, feel free to talk to us whenever you need us, we are here 24 hours to help you.';
			}
			
			$command = 'OpenTicket';
			$postData = array(
				'deptid' => $GLOBALS['fatura_paga_iddepartamento'],
				'subject' => $subject,
				'message' => $message,
				'clientid' => $userid,				
				'markdown' => true,
			);
		
		$results = localAPI($command, $postData, $adminUsername);
		if ($results['result'] == 'success') {
			logactivity('Ticket ID: '.$results['tid'].' - Cliente ID: '.$userid.'');		
		}
		else
		{
			logactivity('Erro API: '.$results['message'].'');	
		}
	}		
			
}	
	
add_hook("OrderPaid",1,"pedidospagos");