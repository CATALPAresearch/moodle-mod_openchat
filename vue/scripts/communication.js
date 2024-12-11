import ajax from 'core/ajax';

export default class Communication{  

    static setPluginName(name){
        Communication.fullName = name;
    }

    static webservice(method, param = {}){          
        if(typeof Communication.fullName !== 'string'){
            throw new Error('No plugin name given at communication class.');
        }              
        return new Promise(           
            (resolve, reject) => {           
                ajax.call([{
                    methodname: `${Communication.fullName}_${method}`,
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