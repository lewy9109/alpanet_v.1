/*
 * Main scripts
 * ----------------------
 */

/*
 * SIDEBAR TOGGLE
 */


$(function(){
    
    $('#sidebar-switcher').click(function() {
        toggleSideBar();
    });

    var toggleSideBar = function() {
        $("#main-sidebar").toggleClass("wide");
        $("#main-content").toggleClass("sidebar-wide");
        $("#logo").toggleClass("wide");
        $("footer").toggleClass("wide");
    };

    /*
     * SIDEBAR MENU
     */
    
    if ($(window).width() >= 768) {
        $('ul#main-buttons > li > .main-button').click(function(event) {
            $('ul#main-buttons > li > .main-button').removeClass('active');
            $(this).addClass('active');
            $('#submenu > .submenu-wrap').removeClass('active');
            $('#submenu > #submenu-' + $(this).data('submenu')).addClass('active');
            $('ul#main-buttons > li > .main-button.active-tmp').removeClass('active-tmp');
            //event.preventDefault();
        });

        $('ul#main-buttons > li > .main-button').hover(
            function() {
                $('#submenu > .submenu-wrap').hide();
                $('#submenu > #submenu-' + $(this).data('submenu')).show();
                $('ul#main-buttons > li > .main-button.active-tmp').removeClass('active-tmp');
                if(!$(this).hasClass('active')) {
                    $(this).addClass('active-tmp');
                }
            }
        );

        $('#submenu').hover(null,
            function(){
                $('#submenu > .submenu-wrap').hide();
                $('#submenu > .active').show();
                $('ul#main-buttons > li > .main-button.active-tmp').removeClass('active-tmp');
            }
        );
    } else {
        $('ul#main-buttons > li > .main-button').click(function(event) {
            $('ul#main-buttons > li > .main-button.active').removeClass('active');
            $(this).addClass('active');
            $('#submenu > .submenu-wrap.active').removeClass('active');
            $('#submenu > #submenu-' + $(this).data('submenu')).addClass('active');
            event.preventDefault();
        });
    }
    
    /**
     * QTIP
     */
    $('.qtip-on').qtip({
        style: { 
            classes: 'qtip-tipsy' 
        },
        position: {
            my: 'top right',
            target: 'mouse',
            adjust: {
                x: 5,
                y: 25
            }
        }
    });
    
    /**
     * Replace demo.com with am1.pl
     */
    var user='klewandowski';
    if (user==='demo_user') {
        replaceDemo();
    }

    /**
     * zoom fx
     */
    $('#pie-chart .tlo').addClass('zoom');

});

var setActiveSidebar = function(submenu, item) {
    $('ul#main-buttons > li > .main-button, #submenu > .submenu-wrap').removeClass('active');
    $('ul#main-buttons > li > .main-button[data-submenu="'+submenu+'"]').addClass('active');
    $('#submenu > #submenu-'+submenu).addClass('active');
    $('#submenu > #submenu-'+submenu+' > ul > li #'+item).addClass('active');
};

var $_GET = {};
var parseGetParameters = function() {
    document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
        function decode(s) {
            return decodeURIComponent(s.split("+").join(" "));
        }

        $_GET[decode(arguments[1])] = decode(arguments[2]);
    }); 
};


var wrapTable = function($table) {
    var $headRow = $('tr', $table).first();
    $headRow.remove();

    if (!$table.has('tbody')) {
        var $otherRows = $('tr', $table);
        $otherRows.remove();

        var $tbody = $('<tbody>');
        $table.append($tbody);
        $tbody.append($otherRows);
    }

    var $thead = $('<thead>');
    $table.prepend($thead);
    $thead.append($headRow);
    
    $table.find("thead > tr > td").each(function() {
        $(this).replaceWith('<th>' + $(this).html() + '</th>');
    });
    
    tableHeaders = [];
    $table.find('thead > tr > th .listtitle').each(function(i) {
        tableHeaders[i] = $(this).text()+': ';
        
    });
    $table.find("tbody > tr").each(function(){
        $(this).find('td').each(function(i){
            $(this).attr('data-content', tableHeaders[i]);
        });
        
    });
    
    $table.addClass('table table-striped table-bordered table-responsive2');
};

var wrapForm = function(form) {
    var inputs = $('select, input', form);
    inputs.addClass('form-control');
    inputs.css('width', 'auto');
    inputs.css('display', 'inline-block');
    inputs.css('margin-right', '20px');
};

var getDefaultDomain = function() {
    var domainsTable;
    var defaultDomain;
    $.ajax({
        url: "/CMD_ADDITIONAL_DOMAINS",
        type: "get",
        async: false,
        success : function(data) {
            domainsTable = $(data).find('table');
        }
    });
    if (domainsTable != '') {
        $('tr', domainsTable).first().remove();
        $('tr', domainsTable).first().remove();
        defaultDomain = domainsTable.find('b').text();
    }
    return defaultDomain;
};

var getDomains = function() {
    var domainsTable;
    var domains = [];
    $.ajax({
        url: "/CMD_ADDITIONAL_DOMAINS",
        type: "get",
        async: false,
        success : function(data) {
            domainsTable = $(data).find('table');
        }
    });
    if (domainsTable != '') {
        $('tr', domainsTable).first().remove();
        $('tr', domainsTable).first().remove();
        $('tr', domainsTable).last().remove();
        $('tr', domainsTable).each(function(){
            domains.push($(this).children('td').first().children('a').text());
        });
    }
    return domains;
};

var getLastMessage = function() {
    var user='klewandowski';
    if (user==='demo_user') {
        return $('<a href="#">Witamy w systemie AM1.PL</a>');
    }
    var messagesTable;
    $.ajax({
        url: "CMD_TICKET?domain=none&sort1=-1",
        type: "get",
        async: false,
        success : function(data) {
            messagesTable = $(data).find('table').last();
        }
    });
    message = messagesTable.find('tbody tr:nth-child(3) td a').first();
    if (message.length!=0){
        return message;
    } else {
        return 'Brak komunikatĂłw';
    }
    
};

var getLastCmd = function(){
    var referrer =  document.referrer;
    referrer = referrer.replace('http://','');
    referrer = referrer.replace('https://','');
    lastChar = referrer.indexOf("?") ? referrer.indexOf("?") : referrer.length;
    return referrer.substring(referrer.indexOf("/")+1,lastChar);
};

var getLastSection = function(){
    cmd = getLastCmd();
    cmdMap = {
        HTM_ADD_DOMAIN: ['domains','add-domain'],
        HTM_EMAIL_POP_CREATE: ['email' ,'pop-create'],
        HTM_EMAIL_POP_MODIFY: ['email' ,'pop-email-accounts'],
        CMD_EMAIL_POP: ['email' ,'pop-email-accounts'],
        HTM_EMAIL_FORWARDER_CREATE: ['email' ,'forwarders'],
        HTM_EMAIL_AUTORESPONDER_CREATE: ['email' ,'autoresponders'],
        CMD_EMAIL_VACATION_CREATE: ['email' ,'vac-messages'],
        HTM_PASSWD: ['dashboard' ,'password'],
        CMD_DOMAIN_POINTER: ['domains' ,'domain-pointers'],
        CMD_SUBDOMAIN: ['domains' ,'subdomain-managment'],
        CMD_CHANGE_DOMAIN: ['domains' ,'chenge-domain'],
        HTM_FTP_CREATE: ['ftp' ,'create-ftp'],
        CMD_FTP: ['ftp' ,'create-ftp'],
        CMD_DOMAIN: ['domains' ,'domain-setup'],
        CMD_ADDITIONAL_DOMAINS: ['domains', 'domain-setup']
    };
    
    return cmdMap[cmd];
};

var replaceDemo = function() {
    $('#logo-cms, a, td, .input-group-addon, option, .form-control, .welcome-desc').each(function(){
        $(this).html($(this).html().replace(/demo.com/g, 'am1.pl'));
        $(this).html($(this).html().replace('DOMAIN=am1.pl', 'domain=demo.com'));
        $(this).html($(this).html().replace('@demo.com', '@am1.pl'));
        $(this).val($(this).val().replace(/demo.com/g, 'am1.pl'));
        $(this).html($(this).html().replace(/domain=am1.pl/g, 'domain=demo.com'));
    });
};
