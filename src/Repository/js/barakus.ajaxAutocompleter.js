if(typeof Autocompleter == 'undefined')
{
    throw("barakus.ajaxAutocompleter.js requires including script.aculo.us' control.js library");
}
    Barakus.AjaxAutocompleter = Class.create(Ajax.Autocompleter, {
      getUpdatedChoices: function() {
        this.startIndicator();
        
        var options = this.options;
        
        var entry = 'ajaxCall[' + options.paramName + ']' + '=' + 
          encodeURIComponent(this.getToken());

        options.parameters = options.callback ?
          options.callback(this.element, entry) : '';

        if(options.defaultParams) 
        {
            options.parameters += '&' + options.defaultParams;            
        }
          
                             
        options.postBody += '&' + entry;
        options.postBody += '&' + options.parameters;

        new Ajax.Request(this.url, options);
      },

      onComplete: function(request) {
        if (this.options.onFinish) {
          this.options.onFinish(request);
        }
        this.updateChoices(request.responseText);
      }
    });
