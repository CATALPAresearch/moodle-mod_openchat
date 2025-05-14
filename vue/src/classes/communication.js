import ajax from 'core/ajax';
export default class Communication{
    static webservice(method, param = {}){          
        return new Promise(           
            (resolve, reject) => {           
                ajax.call([{
                    methodname: "mod_openchat_"+method,
                    args: param?param:{},
                    timeout: 3000,
                    done: function(data){                                                                
                        return resolve(data);
                    },
                    fail: function(error){                                                                
                        return reject(error);
                    }                  
                }]);
            }
        );      
    }
}   
