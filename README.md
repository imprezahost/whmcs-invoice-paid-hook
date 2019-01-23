# whmcs-invoice-paid-hook
WHMCS Invoice Paid Hook

This hook enables the creation of tickets automatically after the customer makes payment of the order.

Installation:

Add the files fatura_paga.php and conf_fatura_paga.php to the /includes/hooks directory of your WHMCS.

Edit the conf_fatura_paga.php file on line 35 and enter the department ID where the tickets will be opened.

Edit the conf_fatura_paga.php file on line 36 and enter the ID of the products you want the ticket to be triggered.

Edit the file fatura_paga.php between lines 46 and 88 to edit the translations and message that the client of each language will receive, you can add more translations.
