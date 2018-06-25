import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs/Observable';
import { map, catchError } from 'rxjs/operators';

/*
  Generated class for the Cin7apiProvider provider.

  See https://angular.io/guide/dependency-injection for more info on providers
  and Angular DI.
*/

const httpOptions = {
  headers: new HttpHeaders({
    // 'Authorization' : 'Basic bmprZ3JvdXBOWjo4N2I1MDczODllNTc0OGZmYjJjZGE1YzBlMmJlZGM1Ng==',
  }),
  params: new HttpParams()
};

@Injectable()
export class Cin7apiProvider {

  //private apiUrl = 'https://api.cin7.com/api/v1/';
  private apiUrl = 'cin7api.php';

  constructor(public http: HttpClient) {
    console.log('Hello Cin7apiProvider Provider');
  }

  queryProducts(params): Observable<{}> {
    const terms = ['fields','where','order','page','rows'];
    console.log('typeof params: ', typeof params);

    let _params: HttpParams = new HttpParams();
    if(typeof params === 'object' ){
      Object.keys(params).filter(key => terms.indexOf(key)> -1)
      .forEach(key => {
        console.log('key: ', key, params[key]);
        _params = _params.append(key,params[key]);
      });
      console.log('_params: ', _params);
      httpOptions['params'] = _params;

    }

    console.log('httpOptions.params: ', httpOptions.params);

    return this.http.get(
      //this.apiUrl+'/Products',
      this.apiUrl,
      httpOptions
    )
    .pipe(
      map(this.extractData),
      catchError(this.handleError)
    );
  }

  private extractData(res: Response) {
    let body = res;
    return body || { };
  }

  private handleError (error: Response | any) {
    let errMsg: string;
    if (error instanceof Response) {
      const err = error || '';
      errMsg = `${error.status} - ${error.statusText || ''} ${err}`;
    } else {
      errMsg = error.message ? error.message : error.toString();
    }
    console.error(errMsg);
    return Observable.throw(errMsg);
  }

}
