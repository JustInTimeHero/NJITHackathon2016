    
        //execute_query();
        
        var queryString; 
        var redditElements = [];
        var promiseRequests = [];
        
        
        function parseQueryString(query){
          queryString = new Array();
          if(query === undefined){
            return;
          }
          var p = query.split('&');
          for(var i = 0; i < p.length; i++){
            var k = p[i].split('=')[0];
            var v = p[i].split('=')[1];
            //console.log(k+ ":" + v);
            queryString[k] = v;
          }
        }
        

        function execute_query(input){
          //console.log("here");
          //allThreadsDone = false;
          parseQueryString(input);
          
          var quantity = queryString['quantity'];
          var category = queryString['category'];
          var subreddit = queryString['subreddit'];
          var time = queryString['time']; 

          if(quantity === undefined){
            quantity = 5;
            queryString['quantity'] = 5;
          }

          if(time === undefined)
            time = "";
          else if(time.includes("hour"))
            time = "hour";
          else if(time.includes("week"))
            time = "week";
          else if(time.includes("month"))
            time = "month";
          else if(time.includes("year"))
            time = "year";
          else if(time.includes("all"))
            time = "all";
          else
            time = "";

          if(subreddit === undefined || subreddit == "reddit"){
            subreddit = "";
            queryString['subreddit'] = "";
          }
          else
          {
            subreddit = "r/" + subreddit;
          }
          
          var url = urlBuilder("https://www.reddit.com",subreddit,category,time);          
          console.log(url);          
                    
          for(var i = 0; i < 1; i++){
              var index = 0;
              if(i != 0){
                index = 0;
                url = urlBuilderCount(url,i*25);
              }else{
                index = (i*25) -1;
              }
              //var y = getThreads(url,index);
              getThreads(url,index)
             
          }
          
          //Promise.all(promiseRequest);
          //console.log("reddit elements: " + redditElements);
          return redditElements;
        }
        
        function urlBuilderCount(url, count){
          return url + "count=" + count; 
        }
        
        function urlBuilder(url,subreddit,filter,time){
          
          var query = "?";
          if(time !== ""){
            query += "sort=" + filter + "&" + "t=" + "time";
          }
          
          var x = "hot"
          
          if(x === filter || filter === undefined){
            url = url + "/" + subreddit + ".json" + query;   
          }
          else if(subreddit == ""){
            url = url + "/" + subreddit + "/" + filter + ".json" + query;
          }else{
            url = url + "/" + filter + ".json" + query;
          }
          return url;          
        }
        
        function getThreads(url,index)
        {  
          console.log(url);
            
            $.getJSON(
            url,
              function parseData(data)
              {  
                var index = 0;
                console.log(data);
                var threads = data.data.children.slice(0, queryString['quantity']);
                console.log(threads);
                for(var i = 0;i < threads.length;i++){
                  console.log(threads[i].data.url);
                  if( threads[i].data.url.includes("https://www.reddit.com/r/" )){
                      console.log(threads[i]);
                      if(i==0 || i == 1){
                        continue;
                      }
                      redditElements[i+index] = new Array();
                      redditElements[i+index]['title'] = threads[i].data.title;
                      redditElements[i+index]['url'] = threads[i].data.url;   
                      //console.log(redditElements[i+index]['url']);
                      //console.log(redditElements.length + ":" + (i+index));
                      addText(i+index);
                  }
                }    
              }
              
               
          ).error(function(){ alert("failed:"); })            

           
          //promiseRequests.push( p );
          //console.log("here");
          //console.log(promiseRequest);
        }
        
        
        
        function addText(index){
          //alert(index);

          $.getJSON(
            redditElements[index]['url']+".json",
              function parseData(data){
                //console.log(data);
                redditElements[index]['text'] = data[0].data.children[0].data.selftext;
                //console.log("Self-text: " + data[0].data.children[0].data.selftext);
                
                //$("#reddit-content").append( '<br>' + redditElements[index]['title'] );
                //$("#reddit-content").append( '<br>' + redditElements[index]['url'] );
                $("#reddit-content").append( '<br>' + redditElements[index]['text'] );
                $("#reddit-content").append( '<hr>' );
            }  
          ).error(function(){ alert("failed:");});

        
        }