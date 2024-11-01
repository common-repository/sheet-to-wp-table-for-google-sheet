
jQuery(function($) {
    'use strict';
    $(document).ready(function() {
        //Has come from enqueue of frontend page loader
        
        var ajax_url = STWT_DATA.ajax_url;
        var ajaxurl = STWT_DATA.ajax_url;
        // console.log(STWT_DATA.datatable_options);

        var options = {
            aaSorting: [
                // [0, 'asc'], //Only one can be active here actually
                [1, 'desc'], //Column number wise sorting. Here 2ns column desc sorting korechi
            ],
            ordering: true, //Ordering feature on or off
            aLengthMenu: [15, 30,50, 100],
            // bPaginate: false, //Pagination on off korar jonno
            layout:{ //Customize Layout of top and bottom of table
                // bottomEnd: ['info','paging', 'search'],
                // bottomStart: ['info','paging', 'search'],
                // topEnd: ['info','paging', 'search'],
                // topStart: ['info','paging', 'search'],

            },
            oLanguage:{
                sSearch: 'Find Here', //Search
                sZeroRecords: 'paoa jayni temon kichu',
                sInfo: 'এখানে দেখাছে _START_ থেকে _END_ মোট _TOTAL_ গুলোর মধ্যে', //_ENTRIES-TOTAL_ it's meaning here entries
                sInfoEmpty: 'showing 0', //Showing 0 to 0 of 0 _ENTRIES-TOTAL_
                sLoadingRecords: 'Loading something....', //Loading...
                sZeroRecords: 'Nothing founded actually', //No matching records found
            }

        };


        var options = STWT_DATA.datatable_options;

        
        $('.sheet-to-wp-table').each(function(){
            var options = STWT_DATA.datatable_options;
            var TableObj = $(this);
            var table = TableObj.DataTable(options);

            // Convert URLs to clickable links
            table.cells().every(function() {
                var cell = $(this.node());
                var originalContent = cell.text();

                var linkedContent = originalContent;
                // Convert [text](url) to buttons
                linkedContent = linkedContent.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a class="sheet-button button" href="$2" target="_blank">$1</a>');

                // Convert remaining URLs to clickable links
                linkedContent = linkedContent.replace(/(?:^|\s)(https?:\/\/[^\s]+)/g, function(match) {
                    // Check if the URL is already part of a link
                    if (!/<a\s/i.test(match)) {
                        return '<a href="' + match.trim() + '" target="_blank">' + match.trim() + '</a>';
                    } else {
                        return match;
                    }
                });
                // Convert email addresses to clickable mailto links
                linkedContent = linkedContent.replace(/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/g, '<a href="mailto:$&">$&</a>');
                var scriptTagFound = /<script\b[^>]*>([\s\S]*?)/gi.test(linkedContent);
                if(scriptTagFound){
                    cell.text(linkedContent);
                }else{
                    cell.html(linkedContent);
                }
                
            });

            // Capitalize table headers and replace underscores with spaces
            TableObj.find('thead tr>th>.dt-column-title').each(function() {
                var headerText = $(this).text().replace(/_/g, ' '); // Replace underscores with spaces
                var capitalizedText = headerText.replace(/\b\w/g, function(match) {
                    return match.toUpperCase();
                }); // Capitalize words

                $(this).text(capitalizedText);
            });
        });
    });
});