import ajax from 'core/ajax';

/**
 *
 * @package    mod_openchat
 * @copyright  2022 Marc Burchart <marc.burchart@tu-dortmund.de> , Kooperative Systeme, FernUniversität Hagen
 * 
 */

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
