# Bucket Dashboard

This is very simple PHP dashboard for [Buckets](https://www.budgetwithbuckets.com).

## Installation

Steps for macOS users.

Clone to your [Valet park](https://laravel.com/docs/9.x/valet#the-park-command) directory.

    $ valet parked
    +-----------+-----+------------------------+------------------------------------------+
    | Site      | SSL | URL                    | Path                                     |
    +-----------+-----+------------------------+------------------------------------------+
    | buckets   |  X  | https://buckets.local  | /Users/urhovaldeko/Sites/valet/buckets   |
    +-----------+-----+------------------------+------------------------------------------+

Pick your buckets using command line

    sqlite3 ~/Documents/Budget.buckets 'select id, name from bucket'
    ...
    10|Clothing
    11|Gifts
    12|Household
    13|Medical
    ...

Replace $budget, $monthly_buc, $accumul_buc, $savings_buc values with your bucket IDs. Set your correct timezone.

Additinally update $curr_pre or $curr_post if you want different currency symbols, and $order if you want to order by name not id.

![Dashboard](/images/screenshot.png)
