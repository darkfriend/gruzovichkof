<template>
  <div>
    <el-autocomplete
            class="inline-input"
            v-model="selected"
            :fetch-suggestions="querySearch"
            placeholder="Введите запрос"
            :trigger-on-focus="false"
    ></el-autocomplete>
  </div>
</template>

<script>
import {Autocomplete} from 'element-ui';
import axios from 'axios';

export default {
  name: 'App',
  components: {
	  'el-autocomplete': Autocomplete,
  },
  data() {
    return {
      selected : '',
    };
  },
  methods : {
    querySearch(queryString, cb) {
  		// console.log('querySearch',queryString,cb);
  		if(queryString.length>3)
  		  this.query(queryString,cb);
  		else
			cb([]);
    },
    query(queryString,cb) {
		axios.get(`/ajax/?q=${queryString}`)
		    .then( responce => {
		    	if(responce.data.success) {
					cb(responce.data.data);
                } else {
					cb([{"value":responce.data.msg}]);
		    		throw new Error(responce.data.msg);
                }
		      console.log(responce);
		    })
		    .catch(error=>console.log(error));
    }
  }
}
</script>

<style lang="scss">
@import "~element-ui/lib/theme-chalk/autocomplete.css";
.el-autocomplete {
  width: 100%;
  display: flex;
}
</style>