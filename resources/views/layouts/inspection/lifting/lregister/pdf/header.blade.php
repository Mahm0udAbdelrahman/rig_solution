<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title></title>
        <link href="{{asset('app-assets/css/material.css')}}" rel="stylesheet">
        <link href="{{asset('app-assets/css/components.css')}}">
        <link href="{{asset('app-assets/css/bootstrap-extended.css')}}" rel="stylesheet">
        <link href="{{asset('app-assets/css/material-extended.css')}}" rel="stylesheet">
        <link href="{{asset('app-assets/css/material-colors.css')}}" rel="stylesheet">
        <link href="{{asset('app-assets/css/custom.css')}}" rel="stylesheet">
        <style type="text/css">
            @page{
                margin: 0; padding: 0;
            }
            table, tr, td, th, tbody, thead, tfoot {
                page-break-inside: avoid !important;
            }
        </style>
    </head>
    <body class="snappy" style="background: white;">
        <header class="paper" style="page-break-before:always;">
            <div class="card" style="border-radius: 0 !important; box-shadow: none !important; margin-bottom: auto !important;background: white !important;">
                <div class="card-content" style="border-radius: 0 !important;background: white !important;">
                    <div class="card-body pb-0" style="border-radius: 0 !important; background: white !important;">
                        <div class="row header-top" style="border-radius: 0 !important; background: white !important;">
                            <table>
                                <tr>
                                    <td width="25%"><img src="{{asset('app-assets/images/logo/logo.png')}}" style="width: 55%;" class="logo-top" /></td>
                                    <td width="57%">
                                        <h5 class="text-bold-600 text-center white mb-0 mt-1">Register of Lifting Appliances and Lifting Accessories</h5>
                                        <p class="text-center text-bold-600 emadnew" style="margin-bottom: 5px;"></p>
                                    </td>
                                    <td  width="18%" style="font-size: 70%; text-align: right;">
                                        <p class="text-bold-700 white">3053 Mahmoud Madkor st. 2nd Floor #11 El-Maerag City, Cairo</p>
                                        <p class="text-bold-700 white" >Phone/Fax: +20 2 24477058</p>
                                        <p class="text-bold-700 white">Cell phone: +20 1032703368</p>
                                        <p class="text-bold-700 white">Email: rse@rigsolutionz.com</p>
                                        <p class="text-bold-700 white">Website: www.rigsolutionz.com</p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <table width="99.7%" style="font-size: 80%; margin: auto; background: white;">
                <tr>
                    <td class="border-dark bg-dark white text-bold-600" style="padding-left: 3px;" width="16.1%">
                        Client Name
                    </td>
                    <td class="border-dark white text-bold-600" width="51.7%" style="padding-left: 3px;">
                        {{$job->client->name}}
                    </td>
                    <td class="border-dark bg-dark white text-bold-600" width="16.1%" style="padding-left: 3px;">
                        Register No.
                    </td>
                    <td class="border-dark white text-bold-600" width="16.1%" style="padding-left: 3px;">
                        {{$job->code}} / {{$code}}
                    </td>
                </tr>
            </table>
            <table width="99.7%" style="font-size: 80%; margin: auto; background: white;">    
                <tr>
                    <td class="border-dark bg-dark white text-bold-600"style="padding-left: 3px;" width="16.1%">
                        Rig / Location
                    </td>
                    <td class="border-dark white text-bold-600"style="padding-left: 3px;" width="19.5%">
                        {{$job->deploc}}
                    </td>
                    <td class="border-dark bg-dark white text-bold-600"style="padding-left: 3px;" width="16.1%">
                        Color Code
                    </td>
                    <td class="border-dark white text-bold-600" style="padding-left: 3px;" width="16.1%">
                        {{$color}}
                    </td>
                    <td class="border-dark bg-dark white text-bold-600" style="padding-left: 3px;" width="16.1%">
                        Date
                    </td>
                    <td class="border-dark white text-bold-600" style="padding-left: 3px;" width="16.1%">
                        {{$date}}
                    </td>
                </tr>
            </table>
            <div style="height: 3px; display: block;"></div>
        </header>
    </body>
</html>