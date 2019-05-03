export default class Gate{

    constructor(user){
        this.user = user;
    }
    
    isAuth(){
    	 return this.user;
    }

    isAdmin(){
        return this.user.role === 'admin';
    }

    isUser(){
        return this.user.role === 'user';
    }

	idUser(){
        if(this.user){
		  return this.user.id;
         }else{
            return false;
         } 
	}
    isVerify(){
      if(this.user){  
        return this.user.email_verified_at;
      }else{
            return false;
         }   
    }
}